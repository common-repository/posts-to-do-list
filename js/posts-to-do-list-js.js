jQuery(document).ready(function($) {

    function posts_to_do_list_content_loading() {
        $("#posts_to_do_list_content").css("background-image", "url("+decodeURIComponent(posts_to_do_list_vars.ajax_loader)+")");
        $("#posts_to_do_list_content").css("background-position", "right top");
        $("#posts_to_do_list_content").css("background-repeat", "no-repeat");
        $("#posts_to_do_list_content_error").empty();
        $("#posts_to_do_list_content_error").css("display", "none");
    }

    function posts_to_do_list_content_loading_clear() {
        $("#posts_to_do_list_content").css("background-image", "");
        $("#posts_to_do_list_content").css("background-position", "");
        $("#posts_to_do_list_content").css("background-repeat", "");
    }

    //Ajax call to reload current page (used for example when deleting items, (un)assigning items
    function posts_to_do_list_reload_current_page() {
        var current_page = Number($("#posts_to_do_list_current_page").text());
        var data = {
            action:                 "posts_to_do_list_ajax_get_page",
            posts_to_do_list_page:  current_page,
            nonce:                  posts_to_do_list_vars.nonce_posts_to_do_list_ajax_get_page
        };

        $.post(decodeURIComponent(posts_to_do_list_vars.ajax_url), data, function(response) {
            if(response.indexOf("Error:") != -1) {
                $("#posts_to_do_list_content_error").html(response.substr(6));
                $("#posts_to_do_list_content_error").css("display", "block");
            } else {
                $("#posts_to_do_list_content").html(response);
            }

            posts_to_do_list_content_loading_clear();

        });
    }

    //On title click, expand content and change background
    $("#posts_to_do_list_content").delegate(".item_to_do_link", "click", function (e) {

        //Prevent link loading
        e.preventDefault();

        if($(this).closest(".item_to_do").children(".item_to_do_content").is(":visible")) {
            $(this).closest(".item_to_do").children(".item_to_do_content").slideUp();
            $(this).closest(".item_to_do").css("background-color", "transparent");
        } else {
            $(this).closest(".item_to_do").children(".item_to_do_content").slideDown();
            $(this).closest(".item_to_do").css("background-color", "#FFFBCC");
        }

    });

    //Marking an item as done
    $("#posts_to_do_list_content").delegate(".mark_as_done", "change", function() {

        posts_to_do_list_content_loading();

        var clicked_item    = jQuery(this);
        var item_id         = clicked_item.val();
        var data            = {
            action:     "posts_to_do_list_ajax_mark_as_done",
            item_id:    item_id,
            checked:    clicked_item.prop("checked"),
            marker:     posts_to_do_list_vars.current_user_ID,
            nonce:      posts_to_do_list_vars.nonce_posts_to_do_list_ajax_mark_as_done
        };

        $.post(decodeURIComponent(posts_to_do_list_vars.ajax_url), data, function(response) {

            if(response.length > 0) {
                $("#posts_to_do_list_content_error").html(response);
                $("#posts_to_do_list_content_error").css("display", "block");
            } else {

                if(clicked_item.prop("checked") == true) {
                    clicked_item.closest(".item_to_do_content").children(".content_left").append("<p class=\"marked_as_done_p\" style=\"font-style: italic;\">" + posts_to_do_list_vars.localized_marked_as_done + " " + posts_to_do_list_vars.current_user_display_name + " (" + posts_to_do_list_vars.current_date + ")</p>");
                    clicked_item.closest(".item_to_do").find(".item_to_do_link").css("color", "");
                    clicked_item.closest(".item_to_do").find(".item_to_do_link").css("text-decoration", "line-through");
                } else {
                    clicked_item.closest(".item_to_do_content").find(".marked_as_done_p").remove();
                    clicked_item.closest(".item_to_do").find(".item_to_do_link").css("text-decoration", "none");
                }

            }

            posts_to_do_list_content_loading_clear();

        });
    });

    //Assigning an item to one's self
    $("#posts_to_do_list_content").delegate(".item_i_ll_take_it", "click", function(e) {
        e.preventDefault();

        posts_to_do_list_content_loading();

        var clicked_item    = $(this);
        var data            = {
            action:     "posts_to_do_list_ajax_i_ll_take_it",
            item_id:    clicked_item.attr("rel"),
            nonce:      posts_to_do_list_vars.nonce_posts_to_do_list_ajax_i_ll_take_it
        };

        $.post(decodeURIComponent(posts_to_do_list_vars.ajax_url), data, function(response) {

            if(response.length > 0) {
                $("#posts_to_do_list_content_error").html(response);
                $("#posts_to_do_list_content_error").css("display", "block");
            } else {
                //clicked_item.closest(".item_to_do_content").find(".assigned").html(posts_to_do_list_vars.current_user_display_name);
                //clicked_item.closest(".item_to_do").find(".item_to_do_link").prepend("* ");
                posts_to_do_list_reload_current_page();
            }

            posts_to_do_list_content_loading_clear();

        });
    });

    //Unassigning an item from one's self
    $("#posts_to_do_list_content").delegate(".item_i_dont_want_it_anymore", "click", function(e) {
        e.preventDefault();

        posts_to_do_list_content_loading();

        var clicked_item    = $(this);
        var data            = {
            action:     "posts_to_do_list_ajax_i_dont_want_it_anymore",
            item_id:    clicked_item.attr("rel"),
            nonce:      posts_to_do_list_vars.nonce_posts_to_do_list_ajax_i_dont_want_it_anymore
        };

        $.post(decodeURIComponent(posts_to_do_list_vars.ajax_url), data, function(response) {

            if(response.length > 0) {
                $("#posts_to_do_list_content_error").html(response);
                $("#posts_to_do_list_content_error").css("display", "block");
            } else {
                //clicked_item.closest(".item_to_do_content").find(".assigned").html("Unassigned");
                //var title = clicked_item.closest(".item_to_do").find(".item_to_do_link").html();
                //clicked_item.closest(".item_to_do").find(".item_to_do_link").html(title.substring(2, title.length));
                posts_to_do_list_reload_current_page();
            }

            posts_to_do_list_content_loading_clear();

        });
    });

    //Deleting an item
    $("#posts_to_do_list_content").delegate(".item_delete", "click", function(e) {
        e.preventDefault();

        var agree = confirm("You are about to delete the selected item permanently. Are you sure you want to continue?");
        if (! agree)
            return false;

        posts_to_do_list_content_loading();

        var clicked_item    = $(this);
        var data            = {
            action:     "posts_to_do_list_ajax_delete_item",
            item_id:    clicked_item.attr("rel"),
            nonce:      posts_to_do_list_vars.nonce_posts_to_do_list_ajax_delete_item
        };

        $.post(decodeURIComponent(posts_to_do_list_vars.ajax_url), data, function(response) {

            if(response.length > 0) {
                $("#posts_to_do_list_content_error").html(response);
                $("#posts_to_do_list_content_error").css("display", "block");
            } else {
                clicked_item.closest(".item_to_do").fadeOut();
                posts_to_do_list_reload_current_page();
            }

            posts_to_do_list_content_loading_clear();

        });
    });

    //User added note
    $("#posts_to_do_list_content").delegate(".ptdl-user-note", "blur", function(e) {
        e.preventDefault();

        posts_to_do_list_content_loading();

        var item    = $(this);
        var data            = {
            action:     "posts_to_do_list_ajax_save_user_note",
            item_id:    item.attr("rel"),
            note:       item.val(),
            nonce:      posts_to_do_list_vars.nonce_posts_to_do_list_ajax_save_user_note
        };

        $.post(decodeURIComponent(posts_to_do_list_vars.ajax_url), data, function(response) {

            if(response.length > 0) {
                $("#posts_to_do_list_content_error").html(response);
                $("#posts_to_do_list_content_error").css("display", "block");
            }

            posts_to_do_list_content_loading_clear();

        });
    });

    /*HANDLING PAGINATION*/
    $("#posts_to_do_list_next_page, #posts_to_do_list_previous_page").click(function (e) {
        e.preventDefault();

        posts_to_do_list_content_loading();

        var clicked_item    = $(this);
        var clicked_id      = $(this).attr("rel");
        var next_page       = Number($("#posts_to_do_list_next_page").attr("rel"));
        var previous_page   = Number($("#posts_to_do_list_previous_page").attr("rel"));
        var current_page    = Number($("#posts_to_do_list_current_page").text());
        var total_pages     = Number($("#posts_to_do_list_total_pages").text());

        //If already on the last page, return
        if((clicked_item.attr("id") == "posts_to_do_list_previous_page" && current_page == 1) ||
            (clicked_item.attr("id") == "posts_to_do_list_next_page" && current_page >= total_pages)) {
            posts_to_do_list_content_loading_clear();
            return false;
        }

        var data = {
            action:                 "posts_to_do_list_ajax_get_page",
            nonce:                  posts_to_do_list_vars.nonce_posts_to_do_list_ajax_get_page,
            posts_to_do_list_page:  clicked_id
        };

        $.post(decodeURIComponent(posts_to_do_list_vars.ajax_url), data, function(response) {
            if(response.indexOf("Error:") != -1) {
                $("#posts_to_do_list_content_error").html(response.substr(6));
                $("#posts_to_do_list_content_error").css("display", "block");
            } else {
                $("#posts_to_do_list_content").html(response);

                if(clicked_item.attr("id") == "posts_to_do_list_next_page") {
                    $("#posts_to_do_list_next_page").attr("rel", (next_page+1));
                    if(current_page != 1) { //If we are not in first page, increment previous page number
                        $("#posts_to_do_list_previous_page").attr("rel", (previous_page+1));
                    }
                    $("#posts_to_do_list_current_page").html(current_page+1);
                } else if(clicked_item.attr("id") == "posts_to_do_list_previous_page") {
                    if(next_page > 1) { //Prevents rel attribute from getting negative or zero
                        $("#posts_to_do_list_next_page").attr("rel", (next_page-1));
                    }
                    if(previous_page > 1) { //Prevents rel attribute from getting negative or zero
                        $("#posts_to_do_list_previous_page").attr("rel", (previous_page-1));
                    }
                    $("#posts_to_do_list_current_page").html(current_page-1);
                }

            }

            posts_to_do_list_content_loading_clear();

        });
    });

    /*ADD NEW ITEM*/
    //When the "Add new post" link is clicked, the related div is shown/hidden depending on the current state
    $("#posts_to_do_list_new").click(function (e) {
        if($("#posts_to_do_list_new_content").is(":visible")) {
            $("#posts_to_do_list_new_content").slideUp();
        } else {
            $("#posts_to_do_list_new_content").slideDown();
        }

        //Prevent link loading
        e.preventDefault();
    });

    //When something is pasted/input field is changed, send an AJAX request to retrieve the title of the URL page
    $("#new_item_URL").bind("input propertychange", function () {

        $("#new_item_loading").css("display", "inline");
        $("#new_item_error").empty();

        var data = {
            action:         "posts_to_do_list_ajax_retrieve_title",
            new_item_url:   $("#new_item_URL").val(),
            nonce:          posts_to_do_list_vars.nonce_posts_to_do_list_ajax_retrieve_title
        };

        $.post(decodeURIComponent(posts_to_do_list_vars.ajax_url), data, function(response) {

            if(response.indexOf("Error:") != -1) {
                $("#new_item_error").html(response.substr(6));
            } else {
                $("#new_item_title").val(response);
                $("#new_item_submit").css("opacity", "1");
                $("#new_item_submit").removeAttr("disabled");
            }

            $("#new_item_loading").css("display", "none");

        });
    });

    //When title field is filled, item can be submitted
    $("#new_item_title").bind("input propertychange", function () {
        $("#new_item_submit").css("opacity", "1");
        $("#new_item_submit").removeAttr("disabled");
    });

    //When textarea notes is clicked, expand it to 5 rows
    $("#new_item_notes").focusin(function() {
        $("#new_item_notes").attr("rows", "5");
    });

    //AJAX for role selection and related users retrieval
    $("#posts_to_do_list_roles").bind("change", function () {

        $("#new_item_loading").css("display", "inline");
        $("#new_item_error").empty();

        var data = {
            action:         "posts_to_do_list_ajax_get_users_by_role",
            user_role:      $("#posts_to_do_list_roles").val(),
            nonce:          posts_to_do_list_vars.nonce_posts_to_do_list_ajax_get_users_by_role
        };

        $.post(decodeURIComponent(posts_to_do_list_vars.ajax_url), data, function(response) {

            if(response.indexOf("Error:") != -1) {
                $("#new_item_error").html(response.substr(6));
            } else {
                $("#posts_to_do_list_author").empty().append(response);
            }

            $("#new_item_loading").css("display", "none");
        });
    });

    //When item is sent for adding, issue an AJAX request
    $("#new_item_submit").click(function (e) {
        e.preventDefault();

        $("#new_item_loading").css("display", "inline");
        $("#new_item_error").empty();

        var data = {
            action:         "posts_to_do_list_ajax_new_item_submit",
            item_url:       $("#new_item_URL").val(),
            item_title:     $("#new_item_title").val(),
            item_adder:     posts_to_do_list_vars.current_user_ID,
            item_keyword:   $("#new_item_keyword").val(),
            item_notes:     $("#new_item_notes").val(),
            item_author:    $("#posts_to_do_list_author option:selected").val(),
            item_priority:  $("#item_priority").val(),
            nonce:          posts_to_do_list_vars.nonce_posts_to_do_list_ajax_new_item_add
        };

        $.post(decodeURIComponent(posts_to_do_list_vars.ajax_url), data, function(response) {

            if(response.length > 0) {
                $("#new_item_error").html(response.substr(6));
            } else {
                $("#posts_to_do_list_no_posts_available").remove();

                var new_item_data = {
                    action:         "posts_to_do_list_ajax_print_item_after_adding",
                    item_url:       $("#new_item_URL").val(),
                    item_title:     $("#new_item_title").val(),
                    item_adder:     posts_to_do_list_vars.current_user_ID,
                    item_keyword:   $("#new_item_keyword").val(),
                    item_notes:     $("#new_item_notes").val(),
                    item_author:    $("#posts_to_do_list_author option:selected").val(),
                    item_priority:  $("#item_priority").val(),
                    nonce:          posts_to_do_list_vars.nonce_posts_to_do_list_ajax_print_item_after_adding
                };

                $.post(decodeURIComponent(posts_to_do_list_vars.ajax_url), new_item_data, function(response) {

                    if(response.indexOf("Error:") != -1) {
                        $("#new_item_error").html(response.substr(6));
                    } else {
                        $("#posts_to_do_list_content").append(response);
                    }

                });

                $("#posts_to_do_list_new_content").slideUp();
                $("#new_item_form").find("input[type=text]").val("");
                $("#new_item_form").find("textarea").val("");

            }

            $("#new_item_loading").css("display", "none");

        });

    });

});