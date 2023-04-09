addEventListener('load', (event) => {
    // Get the modal
    var modal = document.getElementById("modal");
    
    // Get the button that opens the modal
    var buttons = document.querySelectorAll(".modalButton");

    // Get the <span> element that closes the modal
    var span = document.getElementById("close");

    // When the user clicks on the button, open the modal
    buttons.forEach(function(button) {
        button.addEventListener('click', function() {
            var offerName = document.getElementById("offerName");
		    offerName.innerHTML = button.dataset.name;
            var offerDateStart = document.getElementById("offerDateStart");
		    offerDateStart.innerHTML = button.dataset.datestart;
            var offerHourStart = document.getElementById("offerHourStart");
		    offerHourStart.innerHTML = button.dataset.hourstart;
            var offerDateEnd = document.getElementById("offerDateEnd");
		    offerDateEnd.innerHTML = button.dataset.dateend;
            var offerHourEnd = document.getElementById("offerHourEnd");
		    offerHourEnd.innerHTML = button.dataset.hourend;
            var offerPublishedAtDate = document.getElementById("offerPublishedAtDate");
		    offerPublishedAtDate.innerHTML = button.dataset.publishedatdate;
            var offerPublishedAtHour = document.getElementById("offerPublishedAtHour");
		    offerPublishedAtHour.innerHTML = button.dataset.publishedathour;
            var offerText = document.getElementById("offerText");
		    offerText.innerHTML = button.dataset.text;
            var offerTariff = document.getElementById("offerTariff");
		    offerTariff.innerHTML = button.dataset.tariff;
            var offerNbMinimumPlaces = document.getElementById("offerNbMinimumPlaces");
		    offerNbMinimumPlaces.innerHTML = button.dataset.nbminimumplaces;
            //var offerFile1 = document.getElementById("offerFile1");
		    //offerFile1.src = button.dataset.file1;
            modal.style.display = "flex";
        });
    });

    // When the user clicks on <span> (x), close the modal
    span.onclick = function() {
        modal.style.display = "none";
    }

    // When the user clicks anywhere outside of the modal, close it
    window.onclick = function(event) {
        if (event.target == modal) {
            modal.style.display = "none";
        }
    } 
});
