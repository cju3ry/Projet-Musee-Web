function toggleCardContent(id) {
    const content = document.getElementById(`extraContent${id}`);
    const button = document.getElementById(`toggleContent${id}`);
    
    // Débogage : Vérifier si la fonction est bien appelée
    console.log(`Toggle content for ID: ${id}`);
    
   if (content.style.display === "none") {
        content.style.display = "block";
        content.style.whiteSpace = "normal"; // Permet les retours à la ligne
        content.style.wordWrap = "break-word"; // Coupe les mots longs
        content.style.overflowWrap = "break-word"; // Alternative pour les navigateurs plus anciens
        button.textContent = "Moins";
    } else {
        content.style.display = "none";
        button.textContent = "Plus";
    }
}
