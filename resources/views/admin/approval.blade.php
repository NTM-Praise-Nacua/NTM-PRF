@extends('layouts.app')

@push('css')
    <style>
        .inner-shadow {
            box-shadow: inset 0px 0px 5px rgba(0, 0, 0, 0.25);
        }
        .tag-main {
            width: 350px;
        }
        .tagLabel {
            display: inline-flex;
            width: 300px;
            user-select: none;
            background: #1447e6;
            clip-path: polygon(0 0, 
            calc(100% - 50px) 0,
            100% 45%,
            100% 55%,
            calc(100% - 50px) 100%,
            0 100%);
            transition: transform .15s ease-in-out;
            pointer-events: none;
        }
        .tagLabel * {
            pointer-events: auto
        }
        .tagLabel:hover {
            transform: scale(1.03);
        }
        .tagLabel:active {
            cursor: grabbing;
        }
        select[name="department"] option {
            color: black;
        }
        select[name="department"]:focus {
            box-shadow: none;
        }
        .popClose {
            width: 35px;
            height: 35px;
            transform: translate(0%, -50%);
            color: #82181a;
            opacity: 0;
            transition: all 0.15s ease-in-out;
            user-select: none;
            cursor: pointer;
            pointer-events: auto !important;
            z-index: 2;
        }
        .popClose:hover {
            opacity: 1;
        }
        .popClose:active {
            opacity: .75;
        }
        .upload-pdf-container {
            border: 2px dashed lightgray;
            height: 150px;
            width: 250px;
            border-radius: 10px;
            display: flex;
            justify-content: center;
            align-items: center;
            font-size: 16px;
            color: gray;
            cursor: pointer;
        }
        #pdf-list {
            list-style-type: none;
        }
        #pdf-list a {
            text-decoration: none;
        }
        #pdf-list a:hover {
            text-decoration: underline;
        }
    </style>
@endpush

@section('content')
    <x-container pageTitle="Approval Setup">
        <x-section-head headTitle="Approval Process"></x-section-head>

        <div class="row p-3 ">
            <div class="col-3 card-selector-wrapper">
                <div class="card pt-3 px-2 pb-1 mb-2 request-card">
                    <h5 class="card-title">Request Type</h5>
                    <div class="card-body px-0 pb-1">
                        <select name="request_type" id="request_type" class="form-select mb-2">
                            <option value="" selected hidden>Choose Type</option>
                            @forelse ($requestTypes as $type)
                                <option value="{{ $type->id }}">{{ $type->name }}</option>
                            @empty
                                <option value="">No Request Types Found</option>
                            @endforelse
                        </select>
                        <button type="button" class="btn btn-sm btn-primary" id="addOrdering">
                            Add Tag
                        </button>
                    </div>
                </div>


                <div class="card pt-3 px-2">
                    <h5 class="card-title">Add Request</h5>
                    <div class="card-body px-0 pb-2">
                        <form action="{{ route('add.request.type') }}" method="post">
                            @csrf
                            <input type="text" name="name" placeholder="Name" class="form-control mb-2">
                            <button class="btn btn-sm btn-primary" type="submit">Add Type</button>
                        </form>
                    </div>
                </div>
            </div>
            <div class="col">
                <div class="flex-area d-flex flex-column flex-wrap border rounded overflow-x-auto p-4 inner-shadow position-relative" style="height: 350px; row-gap: 16px; column-gap: 16px; background:#f5f5f4;">
                </div>
            </div>
        </div>

        <x-section-head headTitle="Upload PDF Template" :headerButton="true" buttonLabel="Upload"></x-section-head>
        <div class="row mt-3 mx-3">
            <div class="col upload-pdf-container">
                Click or Drop PDF Files
                <input type="file" class="d-none" id="pdf_file" name="pdf_file[]" multiple>
            </div>
            <div class="col">
                <div class="file-details">
                    
                    {{-- <div class="file-pagination">
                        
                    </div> --}}
                </div>
            </div>
        </div>
        <div class="row mt-3 mx-3">
            <ul id="pdf-list" class="col-4">
                
            </ul>
            <div class="col" id="pdfView" style="overflow:auto;">
            </div>
        </div>
    </x-container>
@endsection

@push('js')
    <script>
        $('#addOrdering').on('click', function() {
            addTagLabel();
        });

        $(document).on('click', '.popClose', function() {
            if ($('.tag-main').length <= 0) {
                $('.submit-ordering').remove();
            }
        });

        $(document).on('click', '.submit-ordering', function() {
            const requestType = $('select[name="request_type"]').val();
            if (requestType == "") {
                alertMessage('Choose Request Type', 'warning');
            } else {
                const tagLabel = $('.tagLabel select[name="department"]');
                const emptyTag = tagLabel.filter(function() {
                    return $(this).val() === ""
                });

                let ordering = [];
                let orderingData = [];

                if (emptyTag.length > 0) {
                    alertMessage('Please Select Department(s) in the workflow', 'warning');
                } else {
                    ordering = tagLabel
                        .map(function() {
                            return {
                                orderId: $(this).val(), 
                                id: $(this).data().id
                            };
                        })
                        .get();
                }

                const token = $('meta[name="csrf-token"]').attr('content');
                const formData = new FormData();
                formData.append('requestType', requestType);
                formData.append('_token', token);
                ordering.forEach((order, index) => {
                    formData.append(`ordering[${index}][orderId]`, order.orderId);
                    formData.append(`ordering[${index}][id]`, order.id);
                });
                
                $.ajax({
                    url: '{{ route("prf.add.ordering") }}',
                    type: "POST",
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function (res) {
                        const response = JSON.parse(res);
                        alertMessage(response.message, response.status);
                    }, error: function (xhr) {
                        console.error("error: ", xhr);
                    }
                });
            }
        });

        $('#request_type').on('change', function() {
            $('.flex-area').empty();
            $('.submit-ordering').remove();
            fetchDataFlow($(this).val());
            fetchPDF($(this).val());
        });

        function fetchPDF(id) {
            const token = $('meta[name="csrf-token"]').attr('content');
            const formData = new FormData();
            formData.append('request_id', id);
            formData.append('_token', token);

            $.ajax({
                url: '{{ route("type.pdf.get") }}',
                type: "POST",
                data: formData,
                contentType: false,
                processData: false,
                success: function (response) {
                    const res = JSON.parse(response);

                    const listContainer = $('#pdf-list');
                    listContainer.empty();

                    const container = $('#pdfView');
                    container.empty();

                    const files = res.files;

                    files.forEach((item) => {
                        const listEl = $('<li></li>');
                        const link = $('<a></a>', {
                            href: "javascript:void(0);",
                            'data-src': `{{ asset('storage') }}/${item.path}`,
                            click: (e) => viewPDF(e.currentTarget, $('#pdfView'))
                        });

                        link.text(item.original_name);
                        listContainer.append(listEl.append(link));
                    });
                },
                error: function(xhr, error) {
                    alertMessage("Something went wrong!", "error");
                    console.error('error: ', xhr);
                    console.error('error: ', error);
                }
            });
        }

        // function viewPDF(file) {
        //     const src = file.dataset.src;
        //     const container = $('#pdfView');
        //     container.empty();

        //     const frameEl = $('<iframe></iframe>')
        //         .css({
        //             width: '100%',
        //             height: '1111px',
        //         })
        //         .attr('src', src);
            
        //     container.append(frameEl);
        // }

        function fetchDataFlow(id) {
            const token = $('meta[name="csrf-token"]').attr('content');
            const formData = new FormData();
            formData.append('request_id', id);
            formData.append('_token', token);

            $.ajax({
                url: '{{ route("type.flow") }}',
                type: "POST",
                data: formData,
                contentType: false,
                processData: false,
                success: function (response) {
                    const res = JSON.parse(response);
                    // addTagLabel(res.data);
                    res.data.forEach(item => {
                        addTagLabel(item.ordering, item.id);
                    });
                },
                error: function(xhr, error) {
                    alertMessage("Something went wrong!", "error");
                    console.error('error: ', xhr);
                    console.error('error: ', error);
                }
            });
        }

        function addTagLabel(orderingId = "", prflowId = "") {
            const mainWrapper = $('<div></div>');
            mainWrapper.addClass('tag-main position-relative');
            const divWrapper = $('<div></div>');
            divWrapper.addClass('tagLabel align-items-center relative border fs-4 fw-bold text-white p-3 pe-5 shadow-lg position-relative');
            const close = $('<div></div>');
            close.html('&#10006;');
            close.addClass('popClose position-absolute end-0 top-50 rounded bg-danger text-danger-emphasis shadow fs-4 fw-bolder d-flex justify-content-center align-items-center');
            close.click((e) => {
                remove(e.target);
            });

            const circleWrapper = $('<div></div>');
            circleWrapper.addClass('col-3 circle rounded-circle bg-white align-middle text-black');

            const circle = $('<span></span>');
            const dropdownWrapper = $('<div class="col ps-3"></div>');

            const dropDown = $('<select></select>')
                .attr('name', 'department')
                .addClass('form-select fs-4 fw-bold bg-transparent border-0 text-white')
                .css('background-image','none');
                
            const emptyOpt = $('<option></option>');
            emptyOpt.text('Department')
                .attr('hidden', true)
                .attr('selected', orderingId ? false : true)
                .attr('value', "");
            dropDown.append(emptyOpt);

            const departments = @json($departments);
                
            departments.forEach(el => {
                const option = $('<option></option>');
                option.val(el.id);
                option.text(el.shortcut)
                    .attr('selected', orderingId == el.id ? true : false);
                dropDown.append(option);
            });

            dropDown.data('id', prflowId);

            const tagLabels = $('.tagLabel');
            circle.text(tagLabels.length + 1);

            if ($('.card-selector-wrapper').find('.submit-ordering').length <= 0) {
                const submitBtn = $('<button></button>');
                submitBtn.attr('type', 'button');
                submitBtn.addClass('submit-ordering btn btn-sm btn-success float-end shadow');
                submitBtn.css({
                    zIndex: 5
                });
                submitBtn.text('Save Ordering');
                $('.card-selector-wrapper .request-card .card-body').append(submitBtn);
            }

            dropdownWrapper.append(dropDown);
            circleWrapper.append(circle);
            divWrapper.append(circleWrapper, dropdownWrapper);
            mainWrapper.append(divWrapper, close);

            $('.flex-area').append(mainWrapper);
        }

        function remove(el) {
            const parent = $(el).parent();
            parent.remove();
            recalcNumbers();
        }

        function recalcNumbers() {
            $('.tag-main .circle').each(function(index) {
                $(this).text(index + 1);
            });
        }

        // Drag & Drop
        $(document).ready(function() {
            const dropArea = $('.upload-pdf-container');
            
            dropArea.on('dragenter dragover', (e) => {
                e.preventDefault();
                const current = $(this);
                current.css({
                    opacity: 0.5
                });
            });

            dropArea.on('dragleave', (e) => {
                e.preventDefault();
                const current = $(this);
                current.css({
                    opacity: 1
                });
            });
            
            dropArea.on('drop', (e) => {
                e.preventDefault();
                const current = $(this);
                current.css({
                    opacity: 1
                });
                const droppedFiles = e.originalEvent.dataTransfer.files;

                const pdfFiles = [...droppedFiles].filter(f => f.type === 'application/pdf');

                if (pdfFiles.length === 0) {
                    alertMessage('Upload only PDF Files', 'warning');
                    return;
                }

                renderPaginatedFiles(pdfFiles);
            });
        });

        // Click
        $('.upload-pdf-container').on('click', function() {
            document.getElementById('pdf_file').click();
        });

        $('#pdf_file').on('change', function(e) {
            // const files = this.files[0];
            const files = [...e.target.files];
            // if (files && files.type == "application/pdf") {
            if (files.length > 0) {
                // handlePDFUploads(files);
                renderPaginatedFiles(files)
            } else if (files) {
                alertMessage('Upload only PDF Files', 'warning');
            }
        });
        
        function handlePDFUploads(file) {
            const fileWrapper = $('.file-details');
            fileWrapper.empty();
            const table = $('<table></table>');
            table.addClass('table table-striped mb-0');

            const tbody = $('<tbody></tbody>');
            
            for (const key in file) {
                if (!["name","lastModifiedDate","size"].includes(key)) continue;

                const tr = $('<tr></tr>');
                const tdKey = $('<td></td>');
                const tdValue = $('<td></td>');
                const keyArr = key.split(/(?=[A-Z])/);
                const tempKey = keyArr
                    .map(word => word.charAt(0).toUpperCase() + word.slice(1))
                    .join(" ");
                    
                let tempVal = file[key];

                if (key == "lastModifiedDate") {
                    const date = new Date(tempVal);
                    const dateForm = tempVal.toLocaleDateString("en-US", {
                        month: "short",
                        day: "numeric",
                        year: "numeric"
                    }).replace(/^([A-Za-z]+)\s/, "$1. ");
                    const timeForm = tempVal.toLocaleTimeString("en-US", {
                        hour: "2-digit",
                        minute: "2-digit",
                        hour12: true
                    });
                    tempVal = `${dateForm} ${timeForm}`;
                } else if (key == "size") {
                    tempVal = formatSize(tempVal);
                }
                tdKey.text(tempKey);
                tdValue.text(tempVal);
                tr.append(tdKey, tdValue);

                tbody.append(tr);
            };

            table.append(tbody);

            const submitButton = $('<button></button>');
            submitButton.attr('type', 'button');
            submitButton.addClass('submitFile btn btn-primary btn-sm');
            submitButton.html("Upload File");
            submitButton.data('file', file);
            fileWrapper.append(table, submitButton);
        }

        let uploadedFiles = [];
        let currentIndex = 0;

        function renderPaginatedFiles(files) {
            uploadedFiles = files;
            currentIndex = 0;

            renderCurrentFile();
            renderPaginationControls();
        }

        function renderCurrentFile() {
            const file = uploadedFiles[currentIndex];
            handlePDFUploads(file);   // ← your existing function
        }

        function renderPaginationControls() {
            const container = $('.file-details');
            const paginationContainer = $('<div></div>', {
                class: "file-pagination float-end",
            });
            paginationContainer.empty();

            if (uploadedFiles.length <= 1) return; // no pagination needed

            const prevBtn = $('<button class="btn btn-sm btn-secondary me-2">Prev</button>');
            const nextBtn = $('<button class="btn btn-sm btn-secondary ms-2">Next</button>');
            const label = $(`<span>File ${currentIndex + 1} of ${uploadedFiles.length}</span>`);

            prevBtn.on('click', () => {
                if (currentIndex > 0) {
                    currentIndex--;
                    renderCurrentFile();
                    renderPaginationControls();
                }
            });

            nextBtn.on('click', () => {
                if (currentIndex < uploadedFiles.length - 1) {
                    currentIndex++;
                    renderCurrentFile();
                    renderPaginationControls();
                }
            });

            container.append(paginationContainer.append(prevBtn, label, nextBtn));
        }


        $('.file-details').on('click', '.submitFile', function(e) { 
            const requestType = $('#request_type').val();
            if (requestType != "") {
                const files = uploadedFiles;
                handlePDFSubmit(files, requestType);
            } else {
                alertMessage("Select a Request Type", "warning");
            }
        });

        function formatSize(bytes) {
            const kb = bytes / 1024;
            const mb = kb / 1024;
            const gb = mb / 1024;

            if (gb >= 1) {
                return gb.toFixed(2) + " GB";
            } else if (mb >= 1) {
                return mb.toFixed(2) + " MB";
            } else if (kb >= 1) {
                return kb.toFixed(2) + " KB";
            } else {
                return bytes + " B";
            }
        }

        function handlePDFSubmit(files, type) {
            const token = $('meta[name="csrf-token"]').attr('content');
            const formData = new FormData();

            files.forEach(file => {

                formData.append('files[]', file);
            });

            formData.append('request_type', type);
            formData.append('_token', token);

            $.ajax({
                url: '{{ route("approval.upload.pdf") }}',
                type: "POST",
                data: formData,
                contentType: false,
                processData: false,
                success: function (res) {
                    if (res.message == "successful") {
                        alertMessage("PDF File Uploaded Successfully!", "success");
                    } else {
                        alertMessage("Upload File Fail", "error");
                    }
                },
                error: function(xhr, error) {
                    alertMessage("Something went wrong!", "error");
                    console.error('error: ', xhr);
                    console.error('error: ', error);
                }
            })
        };
    </script>
@endpush