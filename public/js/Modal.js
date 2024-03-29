addEventListener('load', (event) => {
    // Get the modal
    var modal = document.getElementsByClassName("modal")[0];

    // Get the button that opens the modal
    var btn = document.getElementById("deleteButton");

    // Get the <span> element that closes the modal
    var span = document.getElementsByClassName("close")[0];

    // Get the <button> element that closes the modal
    var btnClose = document.getElementsByClassName("btnClose")[0];

    // When the user clicks on the button, open the modal
    btn.onclick = function() {
        modal.style.display = "block";
    }

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
