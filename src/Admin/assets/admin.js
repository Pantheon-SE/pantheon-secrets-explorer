jQuery(document).ready(function ($) {
  $(".pse-toggle-value").click(function () {
    var inputField = $(this).siblings(".pse-secret-value");

    if (inputField.attr("type") === "password") {
      inputField.attr("type", "text");
      $(this).text("Hide");
    } else {
      inputField.attr("type", "password");
      $(this).text("Show");
    }
  });
});
