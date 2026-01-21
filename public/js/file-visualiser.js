// J'utilise la librairie pdf.js mais je devrais sûrement changer et réécrire ce code en PHP

// LIRE LA DOC DE PDF.JS ET RE-REGARDER LE TUTO POUR COMPRENDRE LE CODE !!!

// SRC du tuto : https://www.youtube.com/watch?v=W0mtHWZOGx8

// DOM ELEMENTS
const zoomButton = document.getElementById('#zoomBtn');
const input = document.querySelector('.file-viewer-footer ul li>#inputFile');
const openDocument = document.querySelector('.file-viewer-footer ul li>#openDocument');
const currentPage = document.getElementById('current_page');
const viewer = document.querySelector('.file-viewer-container>.pdf-viewer');

// REFACTORISER LE CODE APRES AVOIR FINI DE L'ECRIRE !

// CONSTS/VARS
const validDocumentExtensions = [".pdf", ".doc", ".docx", ".txt", ".rtf"]; // Je devrais implémenter une var similaire dans l'upload d'image
let currentDocument = {};

// FUNCTIONS
const resetCurrentDocument = () => {

    currentDocument = {
        file: null, // Par défaut, il n'y a pas de fichiers
        numberOfPages: 0, // Le nombre de pages est initialisé à zéro
        currentPage: 1, // Par défaut, je suis placée à la première page du fichier
        zoom: 1, // La valeur du zoom est initialisée à 100%
    }
}

const loadPdf = (data) => {
    resetCurrentDocument();
    const pdfFile = pdfjsLib.getDocument(data);

    pdfFile.promise.then(doc => {
        currentDocument.file = doc;
        currentDocument.numberOfPages = doc;
        viewer.classList.remove('hidden');
        renderCurrentPage();
    })
}

const renderCurrentPage = () => {

    currentPage.file.getPage(currentPage.currentDocument).then(page => {
        const context = viewer.getContext('2d');
        const viewPort = page.getViewport({scale: currentDocument.zoom});
        viewer.height = viewPort.height;
        viewer.width = viewPort.width;

        const renderContext = {
            canvasContext: context,
            viewport: viewPort,
        }
        page.render(renderContext);
    });
    currentPage.innerHTML = currentDocument.currentPage + "of " + currentDocument.numberOfPages;
}

// EVENT LISTENERS
openDocument.addEventListener('click', () => {

    input.click();
});

input.addEventListener('change', (e) => {

    // A utiliser lors du refactor: https://stackoverflow.com/questions/4234589/validation-of-file-extension-before-uploading-file

    const inputFile = e.target.files[0]; // Je cible le fichier à l'index 0 (le premier fichier sélectionné par l'utilisateur)
    
    if(inputFile.type === 'application/pdf') {
        const reader = new FileReader();
        reader.readAsDataURL(inputFile);

        reader.onload = () => {
            loadPdf(reader.result);
            zoomButton.disabled = false;
        }

    } else {
        alert('Veuillez choisir un fichier avec une extension pdf.');
    }
});