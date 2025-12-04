<!-- Modal Delete Info -->
<div class="modal fade" id="deleteModal" role="dialog" aria-labelledby="infoModalTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-danger" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteCategoryModalTitle">{{$title}} Delete</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p>are you sure you want to delete <b><span id="fav-title">this</span></b> {{$title}}?</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <form action="" id="deleteForm" method="POST">
                    @method('DELETE')
                    @csrf                    
                    <button type="submit" class="btn btn-danger">Delete</button>
                </form>
            </div>
        </div>
    </div>
</div>

