document.addEventListener('DOMContentLoaded', function () {
    var deleteModal = document.getElementById('deleteModal');
    if (deleteModal) {
        var modalInstance = new bootstrap.Modal(deleteModal);
        modalInstance.show();
        setTimeout(function () {
            modalInstance.hide();
        }, 2000);
    }
});


document.addEventListener('DOMContentLoaded', function () {
    var deleteModal = document.getElementById('deleteModalTableau');
    if (deleteModal) {
        var modalInstance = new bootstrap.Modal(deleteModal);
        modalInstance.show();
        setTimeout(function () {
            modalInstance.hide();
        }, 2000);
    }
});