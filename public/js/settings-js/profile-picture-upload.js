// DOM ELEMENTS
const inputFile = document.querySelector('.choose-container>#choose-image');
const filePreview = document.querySelector('.preview figure>img');
const fileSizeMessage = document.querySelector('.choose-container>#img-restrictions');
// const filePreview = document.querySelector('.preview>#image');

// CONSTS/VARS
const maxSize = 10 * 1024;

// ARROW FUNCTIONS
const changePreviewOnUpload = (fileSize, fileType, previewURL) => { // Je passe les clés de l'objet fileObjet en tant que param

    if (fileType.includes("image") && fileSize > maxSize) {
      filePreview.setAttribute("src", "../../../assets/imgs/placeholders/placeholder-picture.png");
      fileSizeMessage.textContent = `Le poids du fichier ne dois pas dépasser ${maxSize}Kb (1280 octets).`;
      fileSizeMessage.style.display = 'block';

      // filePreview.style.backgroundImage = previewURL;

    } else if(!fileType.includes("image")) {
      filePreview.setAttribute("src", "../../../assets/imgs/placeholders/placeholder-picture.png");
      fileSizeMessage.textContent = 'Le format du fichier doit être de type "image".';
      fileSizeMessage.style.display = 'block';

    } else {
      filePreview.setAttribute("src", previewURL);
      fileSizeMessage.style.display = 'none';
    }
  }


// EVENT LISTENER
inputFile.addEventListener('change', () => {

  const fileObject = inputFile.files[0]; // .files sert à indiquer que l'élément inputFile renverra un fichier

  const objectURL = URL.createObjectURL(fileObject);
  console.log(objectURL); // Renvoie un lien "blob"
  // console.log(fileObject); // Renvoie un objet FileList contenant toutes les infos du fichier : nom, date de modification, sa taille et son format

  changePreviewOnUpload(fileObject.size, fileObject.type, objectURL);
});