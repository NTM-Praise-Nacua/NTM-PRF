<!-- resources/views/pdf/upload.blade.php -->

<!DOCTYPE html>
<html>
<head>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Upload and Modify PDF</title>
    <script src="{{ asset('js/app.js') }}" defer></script>
    <script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <style>
        body {
            height: 100vh;
        }
        #pdf-container {
            position: relative;
        }
        .draggable-input {
            border: none;
            outline: none;
        }
        .wrapper button {
            vertical-align: top;
        }
    </style>
</head>
<body>
    {{-- <h1>Upload PDF</h1> --}}

    <ul class="pdf-list" style="display: none;">
        <li>
            <a href="javascript:void(0);" data-src="1a45450c-ba5d-4262-9c66-bd5bb6d701f5_Asset Movement Form - Copy.pdf" >test</a>
        </li>
    </ul>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdf.js/2.14.305/pdf.min.js"></script>
    
    <div class="card">
        <div class="card-body wrapper">
            <div id="pdf-container" style="border: 1px solid black; width: auto; display: inline-block;">
                <canvas id="pdf-canvas"></canvas>
            </div>
            <button id="submit-edit">Save to PDF</button>
            <button type="button" id="add-text" class="btn btn-primary">Add Text</button>
            <button type="button" id="increase">A</button>
            <button type="button" id="decrease">a</button>
        </div>
    </div>


    <input type="hidden" id="pos-x">
    <input type="hidden" id="pos-y">
    
    <script>
        $(function() {
            pdfjsLib.GlobalWorkerOptions.workerSrc = 'https://cdnjs.cloudflare.com/ajax/libs/pdf.js/2.14.305/pdf.worker.min.js';
            
            const linkEl = $('.pdf-list a');
            const linkSrc = linkEl.data('src');
            const url = `/storage/pdfs/${linkSrc}`;

            let scale = 1;
            let inputSelect = null;

            pdfjsLib.getDocument(url).promise.then(pdf => {
                pdf.getPage(1).then(page => {
                    window.realPdfWidth = page.view[2];
                    window.realPdfHeight = page.view[3];

                    scale = 1.5
                    const viewport = page.getViewport({scale});
                    const canvas = document.getElementById('pdf-canvas');
                    const context = canvas.getContext('2d');
                    
                    canvas.height = viewport.height;
                    canvas.width = viewport.width;
                    
                    page.render({canvasContext: context, viewport: viewport}).promise.then(() => {
                        enableClickMarkers(canvas, context, scale);
                    });
                });
            });

            function enableClickMarkers(canvas, ctx, scale) {
                // Get coordinates on click
                $("#pdf-canvas").on("click", function(e){
                    const cursor = $(this).css('cursor');

                    if (cursor == "auto") return;

                    const rect = canvas.getBoundingClientRect();

                    // scale to canvas coords
                    const scaleX = canvas.width / rect.width;
                    const scaleY = canvas.height / rect.height;

                    const x_canvas = (e.clientX - rect.left) * scaleX;
                    const y_canvas = (e.clientY - rect.top) * scaleY;

                    // remove render scale
                    const x_unscaled = x_canvas / scale;
                    const y_unscaled = y_canvas / scale;
                    
                    // convert to pdf coords (fpdi)
                    const x_fpdi = x_unscaled;
                    const y_fpdi = y_unscaled;

                    $('.draggable-input').each(function() {
                        if ($(this).val().trim() === '') {
                            $(this).remove();
                        }
                    });

                    const container = $('#pdf-container');

                    const input = $('<input type="text">')
                        .attr({
                            "data-posX": x_fpdi,
                            "data-posY": y_fpdi,
                        })
                        .addClass('draggable-input')
                        .css({
                            position: 'absolute',
                            left: x_canvas + 'px',
                            top: (y_canvas - 12) + 'px',
                            width: '3px',
                            minHeight: '25.02px',
                            zIndex: 10,
                            background: 'transparent',
                            fontSize: "12px",
                            outline: "1px dashed black",
                            whiteSpace: "nowrap",
                            overflow: "auto",
                        });

                    input.on('focus', function() {
                        inputSelect = input;
                    });

                    container.append(input);
                    input.focus();

                    input.on('input', function() {
                        this.style.width = '3px';

                        this.style.width = this.scrollWidth + 'px';
                    });

                    let isDragging = false;
                    let offsetX = 0;
                    let offsetY = 0;

                    input.on('mousedown', function (e) {
                        isDragging = true;

                        const inputLeft = parseFloat(input.css('left'));
                        const inputTop  = parseFloat(input.css('top'));

                        offsetX = e.clientX - inputLeft;
                        offsetY = e.clientY - inputTop;
                    });

                    $(document).on('mousemove', function (e) {
                        if (!isDragging) return;

                        const containerRect = container[0].getBoundingClientRect();
                        const inputWidth = input.outerWidth();
                        const inputHeight = input.outerHeight();
                        const inputFontSize = input[0].style.fontSize;

                        // Desired new position
                        let newLeft = e.clientX - offsetX;
                        let newTop  = e.clientY - offsetY;

                        // Clamp horizontally inside container
                        newLeft = Math.max(0, Math.min(newLeft, containerRect.width - inputWidth));

                        // Clamp vertically inside container
                        newTop = Math.max(0, Math.min(newTop, containerRect.height - inputHeight));

                        input.css({
                            left: newLeft + 'px',
                            top:  newTop + 'px'
                        });

                        const canvas = $('#pdf-canvas')[0];
                        const rect = canvas.getBoundingClientRect();

                        const scaleX = canvas.width / rect.width;
                        const scaleY = canvas.height / rect.height;

                        // relative to canvas
                        const x_canvas = (newLeft / container.width() * rect.width) * scaleX;
                        const y_canvas = ((newTop + parseFloat(inputFontSize)) / container.height() * rect.height) * scaleY; // add back your visual -12px

                        // remove render scale
                        const x_unscaled = x_canvas / scale;
                        const y_unscaled = y_canvas / scale;

                        // convert to PDF coords
                        const x_fpdi = x_unscaled;
                        const y_fpdi = y_unscaled;

                        input.attr({
                            'data-posX': x_fpdi,
                            'data-posY': y_fpdi
                        });
                    });

                    $(document).on('mouseup', function () {
                        isDragging = false;
                    });
                });
            }

            // Send to backend
            $("#submit-edit").on("click", function(){
                const draggableInputs = $('.draggable-input');
                const positions = $('.draggable-input').map(function() {
                    return {
                        posX: parseFloat($(this).attr('data-posX')),
                        posY: parseFloat($(this).attr('data-posY')),
                        text: $(this).val(),
                        fontSize: parseFloat($(this).css('font-size'))
                    }
                }).get(); 

                fetch("/save-pdf", {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json",
                        "X-CSRF-TOKEN": "{{ csrf_token() }}"
                    },
                    body: JSON.stringify({
                        link: linkSrc,
                        positions: positions
                    })
                })
                .then(r=>r.json())
                .then(data=>{
                    if (data.status == 'success') {
                        window.location.reload();
                    }
                });
            });

            $('#add-text').on('click', function() {
                $(this).toggleClass('active');

                enableAddText();
            });

            function enableAddText() {
                const cursor = $('#pdf-canvas').css("cursor");
                if (cursor === "text") {
                    $('#pdf-canvas').css('cursor', 'auto');
                } else {
                    $('#pdf-canvas').css('cursor', 'text');
                }
            }

            
            function updateInputSize(increase, e = null) {
                if (!inputSelect) return;
                
                let fontSize = parseFloat(inputSelect.css('fontSize'));
                // Update font size first
                fontSize = increase ? fontSize + 2 : Math.max(8, fontSize - 2);
                inputSelect.css('fontSize', `${fontSize}px`);

                inputSelect[0].style.width = "3px";
                
                // Then get scrollWidth after font size change
                const width = inputSelect[0].scrollWidth;

                // Apply width
                inputSelect.css('width', `${width}px`);

                inputSelect.blur();
                
                recalculatePdfCoords(inputSelect);
            }

            $('#increase').on('click', function(e) {
                updateInputSize(true, e);
            });

            $('#decrease').on('click', function() {
                updateInputSize(false);
            });

            function recalculatePdfCoords(input) {
                const container = $('#pdf-container');
                const canvas = $('#pdf-canvas')[0];
                const rect = canvas.getBoundingClientRect();

                const scaleX = canvas.width / rect.width;
                const scaleY = canvas.height / rect.height;

                const left = parseFloat(input.css('left'));
                const top  = parseFloat(input.css('top'));

                // Convert container px → canvas px
                const x_canvas = (left / container.width() * rect.width) * scaleX;
                const y_canvas = ((top + parseFloat(input.css('fontSize'))) / container.height() * rect.height) * scaleY;

                // Remove render scale
                const x_unscaled = x_canvas / scale;
                const y_unscaled = y_canvas / scale;

                input.attr({
                    "data-posX": x_unscaled,
                    "data-posY": y_unscaled
                });
            }
        });
    </script>
</body>
</html>
