<div id="modalConfirmEdit" class="modal fade zoomIn" tabindex="-1" aria-labelledby="zoomInModalLabel" aria-hidden="true" style="display: none;">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="zoomInModalLabel">Edit</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body text-center p-5">
                <lord-icon src="https://cdn.lordicon.com/tdrtiskw.json" trigger="loop" colors="primary:#f7b84b,secondary:#405189" style="width:130px;height:130px"></lord-icon>
                <div class="mt-4 pt-4">
                    <h4>Are you sure?</h4>
                    <p class="text-muted">You want to edit this data!</p>
                    <textarea id="getIdEdit" class="id_edit" hidden></textarea>

                    <div class="hstack gap-2 justify-content-center">
                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                        <a href="{{ URL('/module-getById') }}" onclick="location.href=this.href+'/'+document.getElementById('getIdEdit').value;return false;" class="btn btn-warning">Confirm</a>
                    </div> 
                </div>
            </div>
        </div>
    </div>
</div>
