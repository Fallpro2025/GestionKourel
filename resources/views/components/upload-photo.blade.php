@props(['membre', 'currentPhoto' => null])

<div class="upload-photo-container">
    <!-- Zone d'upload -->
    <div id="uploadZone" class="relative border-2 border-dashed border-gray-300 rounded-xl p-6 text-center hover:border-blue-400 hover:bg-blue-50 transition-all duration-300 cursor-pointer">
        <div id="uploadContent">
            @if($currentPhoto)
            <div class="mb-4">
                <img id="currentPhoto" src="{{ $currentPhoto }}" alt="Photo actuelle" class="h-24 w-24 rounded-full mx-auto object-cover">
            </div>
            @else
            <div class="mb-4">
                <div class="h-24 w-24 rounded-full bg-gray-200 mx-auto flex items-center justify-center">
                    <i class="fas fa-user text-gray-400 text-2xl"></i>
                </div>
            </div>
            @endif
            
            <p class="text-sm text-gray-600 mb-2">Cliquez pour changer la photo</p>
            <p class="text-xs text-gray-500">JPG, PNG, GIF, WebP (max 2MB)</p>
        </div>
        
        <div id="uploadProgress" class="hidden">
            <div class="w-full bg-gray-200 rounded-full h-2 mb-2">
                <div id="progressBar" class="bg-blue-600 h-2 rounded-full transition-all duration-300" style="width: 0%"></div>
            </div>
            <p class="text-sm text-gray-600">Upload en cours...</p>
        </div>
    </div>

    <!-- Input file caché -->
    <input type="file" 
           id="photoInput" 
           name="photo" 
           accept="image/*"
           class="hidden"
           onchange="handlePhotoUpload(this)">

    <!-- Actions -->
    <div class="mt-4 flex justify-center space-x-2">
        <button type="button" 
                onclick="document.getElementById('photoInput').click()"
                class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg transition-colors duration-200">
            <i class="fas fa-upload mr-2"></i>Changer la photo
        </button>
        
        @if($currentPhoto)
        <button type="button" 
                onclick="deletePhoto()"
                class="px-4 py-2 bg-red-600 hover:bg-red-700 text-white text-sm font-medium rounded-lg transition-colors duration-200">
            <i class="fas fa-trash mr-2"></i>Supprimer
        </button>
        @endif
    </div>
</div>

<script>
function handlePhotoUpload(input) {
    const file = input.files[0];
    if (!file) return;

    // Validation du fichier
    if (!file.type.startsWith('image/')) {
        if (typeof alerteModerne !== 'undefined') {
            alerteModerne.erreur('Veuillez sélectionner un fichier image valide.');
        } else {
            alert('Veuillez sélectionner un fichier image valide.');
        }
        return;
    }

    if (file.size > 2 * 1024 * 1024) { // 2MB
        if (typeof alerteModerne !== 'undefined') {
            alerteModerne.erreur('Le fichier est trop volumineux. Taille maximale: 2MB.');
        } else {
            alert('Le fichier est trop volumineux. Taille maximale: 2MB.');
        }
        return;
    }

    // Afficher la progression
    document.getElementById('uploadContent').classList.add('hidden');
    document.getElementById('uploadProgress').classList.remove('hidden');

    // Préparer le formulaire
    const formData = new FormData();
    formData.append('photo', file);
    formData.append('_token', document.querySelector('meta[name="csrf-token"]').getAttribute('content'));

    // Simuler la progression
    let progress = 0;
    const progressBar = document.getElementById('progressBar');
    const interval = setInterval(() => {
        progress += Math.random() * 15;
        if (progress > 90) progress = 90;
        progressBar.style.width = progress + '%';
    }, 200);

    // Envoyer la requête
    fetch(`/membres/{{ $membre->id }}/upload-photo`, {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        clearInterval(interval);
        progressBar.style.width = '100%';
        
        setTimeout(() => {
            if (data.success) {
                // Mettre à jour l'image
                const currentPhoto = document.getElementById('currentPhoto');
                if (currentPhoto) {
                    currentPhoto.src = data.photo_url;
                } else {
                    // Créer l'élément image s'il n'existe pas
                    const uploadContent = document.getElementById('uploadContent');
                    const imgDiv = uploadContent.querySelector('.h-24.w-24');
                    imgDiv.innerHTML = `<img id="currentPhoto" src="${data.photo_url}" alt="Photo actuelle" class="h-24 w-24 rounded-full mx-auto object-cover">`;
                }
                
                if (typeof alerteModerne !== 'undefined') {
                    alerteModerne.succes(data.message);
                } else {
                    alert(data.message);
                }
            } else {
                if (typeof alerteModerne !== 'undefined') {
                    alerteModerne.erreur(data.message);
                } else {
                    alert(data.message);
                }
            }
            
            // Réinitialiser l'interface
            document.getElementById('uploadContent').classList.remove('hidden');
            document.getElementById('uploadProgress').classList.add('hidden');
            progressBar.style.width = '0%';
            input.value = '';
        }, 500);
    })
    .catch(error => {
        clearInterval(interval);
        document.getElementById('uploadContent').classList.remove('hidden');
        document.getElementById('uploadProgress').classList.add('hidden');
        progressBar.style.width = '0%';
        
        if (typeof alerteModerne !== 'undefined') {
            alerteModerne.erreur('Erreur lors de l\'upload de la photo.');
        } else {
            alert('Erreur lors de l\'upload de la photo.');
        }
    });
}

function deletePhoto() {
    if (typeof alerteModerne !== 'undefined') {
        alerteModerne.confirmation('Êtes-vous sûr de vouloir supprimer cette photo ?', function(confirme) {
            if (confirme) {
                performDeletePhoto();
            }
        });
    } else {
        if (confirm('Êtes-vous sûr de vouloir supprimer cette photo ?')) {
            performDeletePhoto();
        }
    }
}

function performDeletePhoto() {
    fetch(`/membres/{{ $membre->id }}/photo`, {
        method: 'DELETE',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'Content-Type': 'application/json'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Supprimer l'image
            const currentPhoto = document.getElementById('currentPhoto');
            if (currentPhoto) {
                const imgDiv = currentPhoto.parentElement;
                imgDiv.innerHTML = '<i class="fas fa-user text-gray-400 text-2xl"></i>';
            }
            
            if (typeof alerteModerne !== 'undefined') {
                alerteModerne.succes(data.message);
            } else {
                alert(data.message);
            }
        } else {
            if (typeof alerteModerne !== 'undefined') {
                alerteModerne.erreur(data.message);
            } else {
                alert(data.message);
            }
        }
    })
    .catch(error => {
        if (typeof alerteModerne !== 'undefined') {
            alerteModerne.erreur('Erreur lors de la suppression de la photo.');
        } else {
            alert('Erreur lors de la suppression de la photo.');
        }
    });
}
</script>
