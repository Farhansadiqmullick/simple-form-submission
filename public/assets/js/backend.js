(function ($) {
  $(document).ready(function () {
    editItem = $(
      ".toplevel_page_sfs .form_box_content .column-action span.edit"
    );
    $(".edit-item").on("click", function (e) {
      e.preventDefault();
      var itemId = $(this).data("item-id");
      var nonce = $(this).data("nonce");

      const data = {
        id: itemId,
        action: "sfs_edit_item",
        nonce,
      };

      $.ajax({
        url: sfs_script.ajax_url,
        type: "POST",
        data: data,
        success: function (response) {
          $(".edit-wrapper").addClass("show");
          $(".edit-wrapper").html(response);

          //tag removing
          $(".sfs-edit-form .tag").each(function () {
            $(this).click(function () {
              $(this).remove();
            });
          });

          //Items adding
          $(".sfs-edit-form #items").on("keyup", function (event) {
            if (event.key === "Enter" || event.key === ",") {
              addItem($(this).val().replace(/,/g, ""));
              $(this).val("");
            }
          });

          //removing the item from the container
          function addItem(itemText) {
            if (itemText !== "") {
              var $itemContainer = $("#itemsContainer");
              var $tag = $("<div class='tag'></div>").text(itemText);
              var $removeButton = $(
                "<span class='remove-item'>&times;</span>"
              ).click(function () {
                $tag.remove();
              });
              $tag.append($removeButton);
              $itemContainer.append($tag);
            }
          }
          $(document)
            .on(".sfs-edit-form form-container")
            .submit(function (event) {
              event.preventDefault();
              var formData = {};

              $(this)
                .find("input, textarea")
                .each(function () {
                  var fieldName = $(this).attr("name");
                  if (fieldName === "items") {
                    var selectedValues = [];
                    $(".sfs-edit-form .item-container")
                      .find(".tag")
                      .each(function (index) {
                        var text = $(this).text();
                        if (
                          index ===
                          $(".sfs-edit-form .item-container .tag").length - 1
                        ) {
                          selectedValues.push(
                            text.replace(/,/g, "").replace(/\u00D7/gi, "")
                          );
                        } else {
                          selectedValues.push(text.replace(/\u00D7/gi, ""));
                        }
                      });
                    formData[fieldName] = selectedValues;
                  } else {
                    var fieldValue = $(this).val();
                    formData[fieldName] = fieldValue;
                  }
                });

              formData.action = "sfs_backend_validation";
              formData.id = itemId;
              formData.buyer_ip = sfs_backend_form.user_ip;

              console.log(formData);

              $.ajax({
                url: sfs_backend_form.ajax_url,
                type: "POST",
                data: formData,
                success: function (response) {
                  // Handle the response from the server
                  console.log(response);
                  if(response.success){
                    window.location.reload();
                  }
                },
                error: function (xhr, status, error) {
                  console.log(xhr);
                  console.log(error);
                },
              });
            });
        },
        error: function (xhr, status, error) {
          console.log(xhr);
          console.log(error);
        },
      });
    });
  });
})(jQuery);
