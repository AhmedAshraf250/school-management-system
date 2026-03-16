{{-- Attachment delete confirmation modal --}}
<div class="modal fade" id="Delete_img{{ $attachment->id }}" tabindex="-1"
    aria-labelledby="deleteAttachmentModalLabel{{ $attachment->id }}" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            {{-- Modal header --}}
            <div class="modal-header">
                <h5 style="font-family: 'Cairo', sans-serif;" class="modal-title"
                    id="deleteAttachmentModalLabel{{ $attachment->id }}">
                    {{ trans('Students_trans.Delete_attachment') }}</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            {{-- Modal body --}}
            <div class="modal-body">
                {{-- Delete attachment form --}}
                <form action="{{ route('students.deleteAttachment', ['student' => $student->id, 'attachmentId' => $attachment->id]) }}"
                    method="post">
                    @csrf
                    @method('DELETE')

                    {{-- Confirmation text --}}
                    <h5 style="font-family: 'Cairo', sans-serif;">{{ trans('Students_trans.Delete_attachment_tilte') }}
                    </h5>

                    {{-- Attachment file name preview --}}
                    <input type="text" name="filename" readonly value="{{ $attachment->file_name }}"
                        class="form-control">

                    {{-- Modal actions --}}
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary"
                            data-dismiss="modal">{{ trans('Students_trans.Close') }}</button>
                        <button class="btn btn-danger">{{ trans('Students_trans.submit') }}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
