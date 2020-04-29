<?php
require ('includes/classes/User.php');
require ('includes/header.php');
require ('includes/classes/VideoDetailsFormProvider.php');

session_start();
    $formProvider = new VideoDetailsFormProvider($con);
?>


    <div class="column">

        <?php

        if(!User::isLoggedIn()) {
            echo "Please sign in/up first before upload your video!";
        }

        else {
            echo $formProvider->createUploadForm();
        }

        ?>

    </div>

<script>
    $('form').submit(() => {
        $('#loadingModal').modal("show");
    })
</script>


    <div class="modal fade" id="loadingModal" tabindex="-1" role="dialog" aria-labelledby="loadingModal" aria-hidden="true" data-backdrop="static" data-keyboard="false">
        <div class="modal-dialog" role="document">
            <div class="modal-content">

                <div class="modal-body">
                    Please wait. This might take a while.
                    <img src="assets/img/icons/loading-spinner.gif" alt="">
                </div>

            </div>
        </div>
    </div>


<?php require ('includes/footer.php') ?>