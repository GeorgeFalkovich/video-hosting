function subscribe(userTo, userFrom, button) {

    if(userTo == userFrom) {
        alert("You can't subscribe to yourself!");
        return;
    }

    $.post("ajax/subscribe.php", {userTo: userTo, userFrom: userFrom}).done(function (count) {
        if (count != null) {
            $(button).toggleClass("subscribe unsubscribe");
            let buttonText = $(button).hasClass("subscribe") ? "SUBSCRIBE" : "UNSUBSCRIBE";
            $(button).text(buttonText + " " + count);
        }
        else {
            alert("Something went wrong!");
        }
    });

}