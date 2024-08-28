{{-- resources/views/upload/large.blade.php --}}

<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Large File Upload') }}
        </h2>
    </x-slot>

    <script src="https://cdn.jsdelivr.net/npm/resumablejs@1.1.0/resumable.min.js" ></script>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">

                <div class="m-8 border border-gray-400 rounded-xl" x-data="uploader">
                    <div class="flex flex-col">
                        <h5 class="text-xl text-blue-700 text-center underline my-8">Upload Video File</h5>

                        <div id="upload-container" class="text-center p-2 m-4">
                            <button id="uploadBtn" class="px-4 py-2 bg-blue-500 text-white rounded">Brows File</button>
                        </div>

                        <div class="progress relative pt-1 m-4 hidden" x-ref="progress">
                            <div class="flex mb-2 items-center justify-between">
                                <div>
                                  <span class="text-xs font-semibold inline-block py-1 px-2 uppercase rounded-full text-blue-600 bg-blue-200">
                                    Task in progress
                                  </span>
                                </div>
                                <div class="text-right">
                                  <span class="text-xs font-semibold inline-block text-blue-600">
                                    <div x-ref="progressText">0%</div>
                                  </span>
                                </div>
                            </div>
                            <div class="overflow-hidden h-2 mb-4 text-xs flex rounded bg-gray-200">
                                <div x-ref="progressBar" style="width: 0%" class="progress-bar shadow-none flex flex-col text-center whitespace-nowrap text-white justify-center bg-blue-700"></div>
                            </div>
                        </div>

                        <div class="p-4 ">
                            <video x-ref="player" class="hidden" src="" controls style="width: 100%; height: auto"></video>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>

    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('uploader', () => ({
                started: false,
                uploadBtn: document.getElementById('uploadBtn'),
                progress: 0,
                resumable: new Resumable({
                    target: '{{ route('upload.upload-chunk') }}',
                    query:{_token:'{{ csrf_token() }}'} ,// CSRF token
                    fileType: ['mp4'],
                    chunkSize: 2 * 1024 * 1024, // default is 1*1024*1024, this should be less than your maximum limit in php.ini
                    headers: {
                        'Accept' : 'application/json'
                    },
                    testChunks: false,
                    throttleProgressCallbacks: 1,
                }),
                init() {
                    this.resumable.assignBrowse(this.uploadBtn);
                    this.resumable._refs = this.$refs;
                    this.resumable.on('fileAdded', function (file) { // trigger when file picked
                        this._refs.progress.classList.remove('hidden');
                        this.upload() // to actually start uploading.
                    });
                    this.resumable.on('fileProgress', function (file) { // trigger when file progress update
                        // set width of progress bar
                        let progress = Math.floor(file.progress() * 100) + '%';
                        this._refs.progressBar.style.width = progress;
                        this._refs.progressText.innerText = progress;
                    });
                    this.resumable.on('fileSuccess', function (file, response) { // trigger when file upload complete
                        response = JSON.parse(response)
                        this._refs.player.classList.remove('hidden');
                        this._refs.player.setAttribute('src', response.path);
                    });
                    this.resumable.on('fileError', function (file, response) { // trigger when there is any error
                        alert('file uploading error.')
                    });
                }
            }))
        })
    </script>

</x-app-layout>
