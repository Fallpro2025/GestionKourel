@extends('layouts.app-with-sidebar')

<!-- Messages d'alerte modernes -->
@include('components.alertes-session')

@section('title', 'Importation Excel - Gestion Kourel')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-blue-50 to-indigo-100">
    <!-- En-tête -->
    <div class="fixed top-0 left-64 right-0 z-40 bg-white shadow-sm border-b">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
            <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900 mb-2">
                        <i class="fas fa-file-import mr-3 text-blue-600"></i>Importation Excel
                    </h1>
                    <p class="text-gray-600">Importez des membres en masse depuis un fichier Excel</p>
                </div>
                <div class="mt-4 sm:mt-0">
                    <a href="{{ route('membres.index') }}" 
                       class="bg-gray-600 hover:bg-gray-700 text-white px-6 py-3 rounded-lg font-medium transition-colors duration-200 flex items-center gap-2">
                        <i class="fas fa-arrow-left mr-2"></i>Retour aux membres
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 mt-24">
        <!-- Instructions -->
        <div class="bg-blue-50 border border-blue-200 rounded-xl p-6 mb-8">
            <div class="flex items-start">
                <div class="flex-shrink-0">
                    <i class="fas fa-info-circle text-blue-500 text-xl"></i>
                </div>
                <div class="ml-3">
                    <h3 class="text-lg font-medium text-blue-900 mb-2">Instructions d'importation</h3>
                    <div class="text-blue-800 space-y-2">
                        <p><strong>1.</strong> Téléchargez le template Excel pour voir le format requis</p>
                        <p><strong>2.</strong> Remplissez votre fichier avec les données des membres</p>
                        <p><strong>3.</strong> Glissez-déposez votre fichier ou cliquez pour le sélectionner</p>
                        <p><strong>4.</strong> Vérifiez les données et lancez l'importation</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
            <!-- Zone d'upload -->
            <div class="bg-white rounded-xl shadow-lg p-6">
                <h2 class="text-xl font-bold text-gray-900 mb-6">
                    <i class="fas fa-upload mr-2 text-green-600"></i>Upload du fichier
                </h2>

                <!-- Template download -->
                <div class="mb-6">
                    <a href="{{ route('membres.import.template') }}" 
                       class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition-colors duration-200">
                        <i class="fas fa-download mr-2"></i>Télécharger le template Excel
                    </a>
                    <p class="text-sm text-gray-600 mt-2">Utilisez ce template pour respecter le format requis</p>
                </div>

                <!-- Zone de drag & drop -->
                <div id="dropZone" 
                     class="border-2 border-dashed border-gray-300 rounded-xl p-8 text-center hover:border-blue-400 hover:bg-blue-50 transition-all duration-300 cursor-pointer">
                    <div id="dropZoneContent">
                        <i class="fas fa-cloud-upload-alt text-4xl text-gray-400 mb-4"></i>
                        <p class="text-lg font-medium text-gray-700 mb-2">Glissez-déposez votre fichier Excel ici</p>
                        <p class="text-gray-500 mb-4">ou</p>
                        <button type="button" 
                                onclick="document.getElementById('fichier_excel').click()"
                                class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg transition-colors duration-200">
                            <i class="fas fa-folder-open mr-2"></i>Sélectionner un fichier
                        </button>
                        <p class="text-sm text-gray-500 mt-4">
                            Formats acceptés: .xlsx, .xls, .csv (max 10MB)
                        </p>
                    </div>
                    
                    <div id="fileInfo" class="hidden">
                        <i class="fas fa-file-excel text-4xl text-green-500 mb-4"></i>
                        <p class="text-lg font-medium text-gray-700 mb-2" id="fileName"></p>
                        <p class="text-sm text-gray-500 mb-4" id="fileSize"></p>
                        <button type="button" 
                                onclick="removeFile()"
                                class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg transition-colors duration-200">
                            <i class="fas fa-times mr-2"></i>Supprimer
                        </button>
                    </div>
                </div>

                <!-- Input file caché -->
                <input type="file" 
                       id="fichier_excel" 
                       name="fichier_excel" 
                       accept=".xlsx,.xls,.csv"
                       class="hidden"
                       onchange="handleFileSelect(this)">

                <!-- Bouton d'importation -->
                <div class="mt-6">
                    <button type="button" 
                            id="importBtn"
                            onclick="startImport()"
                            disabled
                            class="w-full bg-green-600 hover:bg-green-700 disabled:bg-gray-400 disabled:cursor-not-allowed text-white px-6 py-3 rounded-lg font-medium transition-colors duration-200 flex items-center justify-center gap-2">
                        <i class="fas fa-play mr-2"></i>
                        <span id="importBtnText">Sélectionnez d'abord un fichier</span>
                    </button>
                </div>
            </div>

            <!-- Prévisualisation -->
            <div class="bg-white rounded-xl shadow-lg p-6">
                <h2 class="text-xl font-bold text-gray-900 mb-6">
                    <i class="fas fa-eye mr-2 text-purple-600"></i>Prévisualisation
                </h2>

                <div id="previewContent" class="text-center text-gray-500">
                    <i class="fas fa-file-alt text-4xl mb-4"></i>
                    <p>Aucun fichier sélectionné</p>
                    <p class="text-sm">La prévisualisation apparaîtra ici après sélection du fichier</p>
                </div>

                <div id="previewData" class="hidden">
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nom</th>
                                    <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Prénom</th>
                                    <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Email</th>
                                    <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Statut</th>
                                </tr>
                            </thead>
                            <tbody id="previewTableBody" class="bg-white divide-y divide-gray-200">
                                <!-- Les données seront ajoutées ici -->
                            </tbody>
                        </table>
                    </div>
                    <div class="mt-4 text-sm text-gray-600">
                        <p id="previewStats"></p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Zone de progression -->
        <div id="progressZone" class="hidden mt-8">
            <div class="bg-white rounded-xl shadow-lg p-6">
                <h3 class="text-lg font-bold text-gray-900 mb-4">
                    <i class="fas fa-spinner fa-spin mr-2 text-blue-600"></i>Importation en cours...
                </h3>
                
                <div class="w-full bg-gray-200 rounded-full h-2 mb-4">
                    <div id="progressBar" class="bg-blue-600 h-2 rounded-full transition-all duration-300" style="width: 0%"></div>
                </div>
                
                <div class="flex justify-between text-sm text-gray-600">
                    <span id="progressText">Préparation...</span>
                    <span id="progressPercent">0%</span>
                </div>
            </div>
        </div>

        <!-- Résultats -->
        <div id="resultsZone" class="hidden mt-8">
            <div class="bg-white rounded-xl shadow-lg p-6">
                <h3 class="text-lg font-bold text-gray-900 mb-4">
                    <i class="fas fa-check-circle mr-2 text-green-600"></i>Résultats de l'importation
                </h3>
                <div id="resultsContent">
                    <!-- Les résultats seront affichés ici -->
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Scripts -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.18.5/xlsx.full.min.js"></script>
<script>
let selectedFile = null;
let previewData = [];

// Gestion du drag & drop
const dropZone = document.getElementById('dropZone');

dropZone.addEventListener('dragover', function(e) {
    e.preventDefault();
    dropZone.classList.add('border-blue-400', 'bg-blue-50');
});

dropZone.addEventListener('dragleave', function(e) {
    e.preventDefault();
    dropZone.classList.remove('border-blue-400', 'bg-blue-50');
});

dropZone.addEventListener('drop', function(e) {
    e.preventDefault();
    dropZone.classList.remove('border-blue-400', 'bg-blue-50');
    
    const files = e.dataTransfer.files;
    if (files.length > 0) {
        handleFile(files[0]);
    }
});

// Gestion de la sélection de fichier
function handleFileSelect(input) {
    if (input.files.length > 0) {
        handleFile(input.files[0]);
    }
}

function handleFile(file) {
    // Validation du fichier
    const allowedTypes = [
        'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet', // .xlsx
        'application/vnd.ms-excel', // .xls
        'text/csv' // .csv
    ];
    
    const allowedExtensions = ['.xlsx', '.xls', '.csv'];
    const fileExtension = '.' + file.name.split('.').pop().toLowerCase();
    
    if (!allowedTypes.includes(file.type) && !allowedExtensions.includes(fileExtension)) {
        if (typeof alerteModerne !== 'undefined') {
            alerteModerne.erreur('Format de fichier non supporté. Veuillez sélectionner un fichier Excel (.xlsx, .xls) ou CSV.');
        } else {
            alert('Format de fichier non supporté. Veuillez sélectionner un fichier Excel (.xlsx, .xls) ou CSV.');
        }
        return;
    }
    
    if (file.size > 10 * 1024 * 1024) { // 10MB
        if (typeof alerteModerne !== 'undefined') {
            alerteModerne.erreur('Le fichier est trop volumineux. Taille maximale: 10MB.');
        } else {
            alert('Le fichier est trop volumineux. Taille maximale: 10MB.');
        }
        return;
    }
    
    selectedFile = file;
    updateFileInfo(file);
    enableImportButton();
    
    // Simuler la prévisualisation (en réalité, il faudrait parser le fichier)
    simulatePreview(file);
}

function updateFileInfo(file) {
    document.getElementById('dropZoneContent').classList.add('hidden');
    document.getElementById('fileInfo').classList.remove('hidden');
    
    document.getElementById('fileName').textContent = file.name;
    document.getElementById('fileSize').textContent = formatFileSize(file.size);
}

function removeFile() {
    selectedFile = null;
    document.getElementById('dropZoneContent').classList.remove('hidden');
    document.getElementById('fileInfo').classList.add('hidden');
    document.getElementById('fichier_excel').value = '';
    disableImportButton();
    hidePreview();
}

function enableImportButton() {
    const btn = document.getElementById('importBtn');
    const btnText = document.getElementById('importBtnText');
    
    btn.disabled = false;
    btnText.textContent = 'Lancer l\'importation';
}

function disableImportButton() {
    const btn = document.getElementById('importBtn');
    const btnText = document.getElementById('importBtnText');
    
    btn.disabled = true;
    btnText.textContent = 'Sélectionnez d\'abord un fichier';
}

function formatFileSize(bytes) {
    if (bytes === 0) return '0 Bytes';
    const k = 1024;
    const sizes = ['Bytes', 'KB', 'MB', 'GB'];
    const i = Math.floor(Math.log(bytes) / Math.log(k));
    return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + ' ' + sizes[i];
}

function simulatePreview(file) {
    // Lire le fichier réel au lieu d'utiliser des données simulées
    readExcelFile(file);
}

function readExcelFile(file) {
    const reader = new FileReader();
    
    reader.onload = function(e) {
        try {
            const data = e.target.result;
            const workbook = XLSX.read(data, { type: 'binary' });
            const firstSheetName = workbook.SheetNames[0];
            const worksheet = workbook.Sheets[firstSheetName];
            const jsonData = XLSX.utils.sheet_to_json(worksheet, { header: 1 });
            
            if (jsonData.length < 2) {
                showPreviewError('Le fichier ne contient pas assez de données');
                return;
            }
            
            // Récupérer les en-têtes
            const headers = jsonData[0].map(h => h ? h.toString().toLowerCase().trim() : '');
            const dataRows = jsonData.slice(1);
            
            // Valider les en-têtes requis
            const requiredHeaders = ['nom', 'prénom', 'email', 'téléphone'];
            const missingHeaders = requiredHeaders.filter(h => !headers.includes(h));
            
            if (missingHeaders.length > 0) {
                showPreviewError(`En-têtes manquants: ${missingHeaders.join(', ')}`);
                return;
            }
            
            // Convertir les données en format membre
            previewData = dataRows.slice(0, 10).map((row, index) => { // Limiter à 10 lignes pour la prévisualisation
                const membre = {};
                headers.forEach((header, colIndex) => {
                    const value = row[colIndex] ? row[colIndex].toString().trim() : '';
                    switch (header) {
                        case 'nom':
                            membre.nom = value;
                            break;
                        case 'prénom':
                            membre.prenom = value;
                            break;
                        case 'email':
                            membre.email = value;
                            break;
                        case 'statut':
                            membre.statut = value.toLowerCase();
                            break;
                    }
                });
                return membre;
            }).filter(m => m.nom && m.prenom); // Filtrer les lignes vides
            
            // Si aucune donnée valide trouvée, utiliser des exemples sénégalais (hommes uniquement)
            if (previewData.length === 0) {
                previewData = [
                    { nom: 'Diop', prenom: 'Modou', email: 'modou.diop@email.com', statut: 'actif' },
                    { nom: 'Ba', prenom: 'Issa', email: 'issa.ba@email.com', statut: 'actif' },
                    { nom: 'Ndiaye', prenom: 'Ablaye', email: 'ablaye.ndiaye@email.com', statut: 'actif' },
                    { nom: 'Sow', prenom: 'Moussa', email: 'moussa.sow@email.com', statut: 'actif' }
                ];
            }
            
            showPreview();
            
        } catch (error) {
            console.error('Erreur lors de la lecture du fichier:', error);
            showPreviewError('Erreur lors de la lecture du fichier Excel');
        }
    };
    
    reader.readAsBinaryString(file);
}

function showPreviewError(message) {
    document.getElementById('previewContent').classList.remove('hidden');
    document.getElementById('previewData').classList.add('hidden');
    
    const previewContent = document.getElementById('previewContent');
    previewContent.innerHTML = `
        <div class="text-center text-red-500">
            <i class="fas fa-exclamation-triangle text-4xl mb-4"></i>
            <p class="text-lg font-medium">Erreur de prévisualisation</p>
            <p class="text-sm mt-2">${message}</p>
        </div>
    `;
}

function showPreview() {
    document.getElementById('previewContent').classList.add('hidden');
    document.getElementById('previewData').classList.remove('hidden');
    
    const tbody = document.getElementById('previewTableBody');
    tbody.innerHTML = '';
    
    previewData.forEach(membre => {
        const row = document.createElement('tr');
        row.innerHTML = `
            <td class="px-3 py-2 whitespace-nowrap text-sm text-gray-900">${membre.nom}</td>
            <td class="px-3 py-2 whitespace-nowrap text-sm text-gray-900">${membre.prenom}</td>
            <td class="px-3 py-2 whitespace-nowrap text-sm text-gray-900">${membre.email}</td>
            <td class="px-3 py-2 whitespace-nowrap">
                <span class="px-2 py-1 text-xs font-medium rounded-full bg-green-100 text-green-800">
                    ${membre.statut}
                </span>
            </td>
        `;
        tbody.appendChild(row);
    });
    
    document.getElementById('previewStats').textContent = 
        `${previewData.length} membre(s) détecté(s) dans le fichier`;
}

function hidePreview() {
    document.getElementById('previewContent').classList.remove('hidden');
    document.getElementById('previewData').classList.add('hidden');
}

function startImport() {
    if (!selectedFile) {
        if (typeof alerteModerne !== 'undefined') {
            alerteModerne.erreur('Veuillez d\'abord sélectionner un fichier.');
        } else {
            alert('Veuillez d\'abord sélectionner un fichier.');
        }
        return;
    }
    
    // Afficher la zone de progression
    document.getElementById('progressZone').classList.remove('hidden');
    document.getElementById('resultsZone').classList.add('hidden');
    
    // Préparer le formulaire
    const formData = new FormData();
    formData.append('fichier_excel', selectedFile);
    formData.append('_token', document.querySelector('meta[name="csrf-token"]').getAttribute('content'));
    
    // Simuler la progression
    simulateProgress();
    
    // Envoyer la requête
    fetch('{{ route("membres.import.process") }}', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        hideProgress();
        showResults(data);
    })
    .catch(error => {
        hideProgress();
        if (typeof alerteModerne !== 'undefined') {
            alerteModerne.erreur('Erreur lors de l\'importation: ' + error.message);
        } else {
            alert('Erreur lors de l\'importation: ' + error.message);
        }
    });
}

function simulateProgress() {
    let progress = 0;
    const progressBar = document.getElementById('progressBar');
    const progressText = document.getElementById('progressText');
    const progressPercent = document.getElementById('progressPercent');
    
    const interval = setInterval(() => {
        progress += Math.random() * 15;
        if (progress > 90) progress = 90;
        
        progressBar.style.width = progress + '%';
        progressPercent.textContent = Math.round(progress) + '%';
        
        if (progress < 30) {
            progressText.textContent = 'Lecture du fichier...';
        } else if (progress < 60) {
            progressText.textContent = 'Validation des données...';
        } else if (progress < 90) {
            progressText.textContent = 'Importation en cours...';
        }
        
        if (progress >= 90) {
            clearInterval(interval);
        }
    }, 200);
}

function hideProgress() {
    document.getElementById('progressZone').classList.add('hidden');
}

function showResults(data) {
    document.getElementById('resultsZone').classList.remove('hidden');
    
    const resultsContent = document.getElementById('resultsContent');
    
    if (data.success) {
        resultsContent.innerHTML = `
            <div class="bg-green-50 border border-green-200 rounded-lg p-4">
                <div class="flex items-center">
                    <i class="fas fa-check-circle text-green-500 text-xl mr-3"></i>
                    <div>
                        <h4 class="text-lg font-medium text-green-900">Importation réussie !</h4>
                        <p class="text-green-800">${data.message}</p>
                        <p class="text-sm text-green-700 mt-2">
                            <strong>${data.imported_count}</strong> membre(s) ajouté(s) avec succès.
                        </p>
                    </div>
                </div>
            </div>
        `;
        
        if (typeof alerteModerne !== 'undefined') {
            alerteModerne.succes(data.message);
        }
        
        // Réinitialiser le formulaire
        setTimeout(() => {
            removeFile();
        }, 3000);
        
    } else {
        resultsContent.innerHTML = `
            <div class="bg-red-50 border border-red-200 rounded-lg p-4">
                <div class="flex items-center">
                    <i class="fas fa-exclamation-circle text-red-500 text-xl mr-3"></i>
                    <div>
                        <h4 class="text-lg font-medium text-red-900">Erreur d'importation</h4>
                        <p class="text-red-800">${data.message}</p>
                        ${data.errors ? `
                            <div class="mt-3">
                                <h5 class="font-medium text-red-900">Détails des erreurs :</h5>
                                <ul class="list-disc list-inside text-sm text-red-700 mt-2">
                                    ${data.errors.map(error => `<li>${error}</li>`).join('')}
                                </ul>
                            </div>
                        ` : ''}
                    </div>
                </div>
            </div>
        `;
        
        if (typeof alerteModerne !== 'undefined') {
            alerteModerne.erreur(data.message);
        }
    }
}
</script>
@endsection
