$(document).ready(function () {
    $('#error-modal').modal('show');

    var loginViewModel = new kendo.observable({
        email: "",
        password: "",
        success: false,
        error: false,
        error_message: "",
        login_text: "Đăng nhập",
        login: function () {
            var self = this;
            self.set("login_text", "Đang đăng nhập");
            // Send
            jQuery.post("admin-login.php", {
                email: this.email,
                password: this.password
            }, function (response) {
                if (!response.success) {
                    // if error
                    self.set("success", false);
                    self.set("error", true);
                    self.set("error_message", response.error_message);
                    self.set("login_text", "Đăng nhập");
                } else {
                    self.set("error", false);
                    self.set("success", true);
                    // Redirect to console
                }

                self.set("email", "");
                self.set("password", "");
            });
            return false;
        }
    });

    kendo.bind($('#login-form'), loginViewModel);
});