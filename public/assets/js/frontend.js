(function ($) {
  $(document).ready(function () {
    $(".sfs-form .form-container").submit(function (event) {
      event.preventDefault();
      const validation = js_validation(".sfs-form .form-container");
      if (!validation) {
        return;
      }

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

  // Js validation
  function js_validation(data) {
    // Define regular expressions for validation
    var numberPattern = /^[0-9]+(\.[0-9]{2})?$/;
    var textPattern = /^[a-zA-Z\s×]+$/;
    var allPattern = /^[a-zA-Z\s\d.×]+$/;
    var emailPattern = /^[a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,4}$/;

    // Validation flag
    var isValid = true;

    // Validate Amount (only numbers)
    var amount = $(data).find("#amount").val();
    if (!numberPattern.test(amount)) {
      alert("Amount must contain only decimal numbers.");
      isValid = false;
    }

    // Validate Buyer (only text, spaces, and numbers, not more than 20 characters)
    var buyer = $(data).find("#buyer").val();
    if (!allPattern.test(buyer) || buyer.length > 20) {
      alert(
        "Buyer must contain only text, spaces, and numbers (max 20 characters)."
      );
      isValid = false;
    }

    // Validate Receipt ID (only text, no number)
    var receiptId = $(data).find("#receipt_id").val();
    if (!textPattern.test(receiptId)) {
      alert("Receipt ID must contain only text (no numbers).");
      isValid = false;
    }

    // Validate Items (only text, no number)
    var items = $(data).find("#items").val();
    tags = "";
    $(data)
      .find(".tag")
      .each(function () {
        tags += $(this).text();
      });
    if (!allPattern.test(tags)) {
      alert("Tags must contain only text(no number).");
      isValid = false;
    }
    // Validate Buyer Email (only emails)
    var buyerEmail = $(data).find("#buyer_email").val();
    if (!emailPattern.test(buyerEmail)) {
      alert("Please enter a valid email address.");
      isValid = false;
    }

    // Validate Note (anything, not more than 30 words, and can include unicode characters)
    var note = $(data).find("#note").val();
    if (note.length > 30) {
      alert("Note cannot exceed 30 words.");
      isValid = false;
    }

    // Validate City (only text and spaces, no numbers)
    var city = $(data).find("#city").val();
    if (!textPattern.test(city)) {
      alert("City must contain only text and spaces (no numbers).");
      isValid = false;
    }

    // Validate Phone (only numbers)
    var phone = $(data).find("#phone").val();
    if (!numberPattern.test(phone) && phone.length <= 15) {
      alert("Phone must contain only numbers and should be valid.");
      isValid = false;
    }

    // Validate Entry By (only numbers)
    var entryBy = $(data).find("#entry_by").val();
    if (!numberPattern.test(entryBy)) {
      alert("Entry By must contain only numbers.");
      isValid = false;
    }

    // If all fields are valid, submit the form
    return isValid;
  }
})(jQuery);
