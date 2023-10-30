<x-main-layout class="w-full max-w-5xl mx-auto pt-6">
    {{-- <h2 class="text-lg">Add Images to "{{ $folder->name }}"</h2>
            <form action="">
                @csrf
            </form> --}}

    <div class="card-header" style="background: gray; color:#f1f7fa; font-weight:bold;">
        Laravel 10 Multiple Image Upload Example - Laravelia
    </div>
    <div class="card-body">
        <form action="{{ route('image.store') }}" method="post" enctype="multipart/form-data">
            @csrf
            <div class="form-group">
                <label for="document">Documents</label>
                <input type="hidden" name="folder" value="{{ $folder->id }}">
                <div class="needsclick dropzone" id="document-dropzone">
                </div>
                <button type="submit" class="btn btn-primary mt-5">Submit</button>
        </form>
    </div>

    <link href="https://cdnjs.cloudflare.com/ajax/libs/dropzone/5.5.1/min/dropzone.min.css" rel="stylesheet" />
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.3/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/dropzone/5.5.1/min/dropzone.min.js"></script>


    <script>
        var uploadedDocumentMap = {}
        Dropzone.options.documentDropzone = {
            url: "{{ route('uploads') }}",
            maxFilesize: 2, // MB
            addRemoveLinks: true,
            autoDiscover: false,
            headers: {
                'X-CSRF-TOKEN': "{{ csrf_token() }}"
            },
            success: function(file, response) {
                $('form').append('<input type="hidden" name="document[]" value="' + file.name + '">')
                uploadedDocumentMap[file.name] = file.name
                return file.previewElement.classList.add("dz-success");
            },
            removedfile: function(file) {
                file.previewElement.remove()
                var name = ''
                if (typeof file.file_name !== 'undefined') {
                    name = file.file_name
                } else {
                    name = uploadedDocumentMap[file.name]
                }
                $('form').find('input[name="document[]"][value="' + name + '"]').remove()
            },
            init: function() {
                @if (isset($project) && $project->document)
                    var files =
                        {!! json_encode($project->document) !!}
                    for (var i in files) {
                        var file = files[i]
                        this.options.addedfile.call(this, file)
                        file.previewElement.classList.add('dz-complete')
                        $('form').append('<input type="hidden" name="document[]" value="' + file.file_name + '">')
                    }
                @endif
            }
        }
    </script>

</x-main-layout>
