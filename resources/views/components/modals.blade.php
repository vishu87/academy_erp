<div id="{{$id}}" class="modal fade in small-modal modal-overflow" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog {{$size}}" role="document"> 
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">{{$title}}</h4>
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="icons icon-close"></i></button>
            </div>
            <div class="modal-body">
                {{$slot}}
            </div>
        </div>
    </div>
</div>