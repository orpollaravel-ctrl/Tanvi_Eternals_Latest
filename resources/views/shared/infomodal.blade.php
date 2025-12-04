<!-- Modal Delete Info -->
<div class="modal fade" id="infoModal" role="dialog" aria-labelledby="infoModalTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-info" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteCategoryModalTitle">{{$title}} Delete</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p>You can not delete <b><span id="fav-title">this</span></b> {{$title}}. It is in use.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-info" data-dismiss="modal">Ok</button>                
            </div>
        </div>
    </div>
</div>


