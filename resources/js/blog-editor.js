import {
    Alignment,
    Autoformat,
    BlockQuote,
    Bold,
    ClassicEditor,
    Essentials,
    Heading,
    Image,
    ImageCaption,
    ImageResize,
    ImageStyle,
    ImageToolbar,
    ImageUpload,
    Italic,
    Link,
    List,
    MediaEmbed,
    Paragraph,
    RemoveFormat,
    SimpleUploadAdapter,
    Strikethrough,
    Table,
    TableToolbar,
    Underline,
} from 'ckeditor5';
import 'ckeditor5/ckeditor5.css';

const initialiseBlogEditor = () => {
    const textarea = document.querySelector('#content[data-blog-editor]');
    if (!textarea || textarea.dataset.editorReady === 'true') return;

    textarea.dataset.editorReady = 'true';
    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content;

    ClassicEditor.create(textarea, {
        licenseKey: 'GPL',
        plugins: [
            Alignment,
            Autoformat,
            BlockQuote,
            Bold,
            Essentials,
            Heading,
            Image,
            ImageCaption,
            ImageResize,
            ImageStyle,
            ImageToolbar,
            ImageUpload,
            Italic,
            Link,
            List,
            MediaEmbed,
            Paragraph,
            RemoveFormat,
            SimpleUploadAdapter,
            Strikethrough,
            Table,
            TableToolbar,
            Underline,
        ],
        toolbar: {
            items: [
                'undo', 'redo', '|',
                'heading', '|',
                'bold', 'italic', 'underline', 'strikethrough', '|',
                'alignment', 'link', '|',
                'bulletedList', 'numberedList', 'blockQuote', '|',
                'insertTable', 'uploadImage', 'mediaEmbed', '|',
                'removeFormat',
            ],
            shouldNotGroupWhenFull: false,
        },
        simpleUpload: {
            uploadUrl: textarea.dataset.uploadUrl,
            headers: {
                'X-CSRF-TOKEN': csrfToken,
                Accept: 'application/json',
            },
        },
        image: {
            toolbar: [
                'imageTextAlternative',
                'toggleImageCaption',
                '|',
                'imageStyle:inline',
                'imageStyle:block',
                'imageStyle:side',
                '|',
                'resizeImage',
            ],
        },
        table: {
            contentToolbar: ['tableColumn', 'tableRow', 'mergeTableCells'],
        },
        link: {
            addTargetToExternalLinks: true,
            defaultProtocol: 'https://',
        },
        placeholder: 'Write your article here...'
    }).catch((error) => {
        textarea.dataset.editorReady = 'false';
        console.error('Unable to start the blog editor.', error);
    });
};

if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', initialiseBlogEditor);
} else {
    initialiseBlogEditor();
}
