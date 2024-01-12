(function ($) {
  $(document).ready(function () {
    $(".form-container").submit(function (event) {
      event.preventDefault();
      var formData = {};

      // Update the input field with +880 phone number
      $(this)
        .find("input, textarea")
        .each(function () {
          var fieldName = $(this).attr("name");
          if (fieldName == "phone") {
            var phoneNumberInput = $(this);
            var phoneNumber = phoneNumberInput.val();
            if (phoneNumber) {
              phoneNumber = "+880" + phoneNumber.toString();
              formData[fieldName] = phoneNumber;
            }
          } else if (fieldName === "items") {
            var selectedValues = [];
            $(".item-container")
              .find(".tag")
              .each(function (index) {
                var text = $(this).text();
                if (index === $(".item-container .tag").length - 1) {
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

      formData.action = "sfs_frontend_validation";
      formData.nonce = sfs_script.nonce;
      formData.buyer_ip = sfs_script.user_ip;

      console.log(formData);

      $.ajax({
        url: sfs_script.ajax_url,
        type: "POST",
        data: formData,
        success: function (response) {
          console.log(response);
          // Handle the response from the server
          if (response.success) {
            alert("Thanks! Your data has been successfully saved.");
          }
          if (response.proceed) {
            alert("Oops! your value can be saved only once in a day");
          }
        },
        error: function (xhr, status, error) {
          console.log(xhr);
          console.log(error);
          alert("Sorry! " + error + "happened, data not saved");
        },
      });
      $(".form-container")[0].reset();
    });
  });

  //Items adding
  $("#items").on("keyup", function (event) {
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
      var $removeButton = $("<span class='remove-item'>&times;</span>").click(
        function () {
          $tag.remove();
        }
      );
      $tag.append($removeButton);
      $itemContainer.append($tag);
    }
  }

  //get user ip
  function getUserIP() {
    return new Promise(function (resolve, reject) {
      $.getJSON("https://api.ipify.org?format=json", function (data) {
        resolve(data.ip);
      }).fail(function () {
        reject("Failed to retrieve IP address");
      });
    });
  }

  function canSubmitForm() {
    return getUserIP()
      .then(function (ip) {
        var existingTime = localStorage.getItem(ip);

        if (!existingTime) {
          // If no timestamp is stored, allow the form submission and store the current time
          localStorage.setItem(ip, Date.now().toString());
          console.log("Cookie true");
          return true;
        } else {
          var lastSubmitTime = parseInt(existingTime);
          var currentTime = Date.now();
          var minutes = 5 * 60 * 1000; // 5 minutes in milliseconds
          if (currentTime - lastSubmitTime < minutes) {
            alert("You can only submit the form once in a day.");
            return false;
          } else {
            // If more than 5 minutes have passed, update the timestamp and allow the form submission
            localStorage.setItem(ip, currentTime.toString());
            console.log("Cookie true");
            return true;
          }
        }
      })
      .catch(function (error) {
        console.error(error);
      });
  }
})(jQuery);
