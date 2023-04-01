addEventListener('load', (event) => {
    // Get the modal
    var modal = document.getElementsByClassName("modalImage")[0];

    // Get the button that opens the modal
    var btns = document.querySelectorAll(".deleteImageButton");

    // Get the <span> element that closes the modal
    var span = document.getElementsByClassName("closeImage")[0];

    // Get the <button> element that closes the modal
    var btnClose = document.getElementsByClassName("btnCloseImage")[0];

    // When the user clicks on the button, open the modal
    btns.forEach(function(button) {
        button.addEventListener('click', function() {
            mediaId = button.dataset.mediaId;
            mediaIdInput.value = mediaId;
            modal.style.display = "block";
        });
    });

    // When the user clicks on <span> (x), close the modal
    span.onclick = function() {
        modal.style.display = "none";
    }

    // When the user clicks on <button> (retour), close the modal
    btnClose.onclick = function() {
        modal.style.display = "none";
    }

    // When the user clicks anywhere outside of the modal, close it
    window.onclick = function(event) {
        if (event.target == modal) {
            modal.style.display = "none";
        }
    } 
});
