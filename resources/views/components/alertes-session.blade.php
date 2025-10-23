{{-- Composant pour les alertes de session modernes --}}
@if(session('success'))
    <div class="mb-6">
        <script>
            // Attendre que le système d'alertes soit chargé
            function afficherAlerteSucces() {
                if (typeof alerteModerne !== 'undefined' && alerteModerne) {
                    alerteModerne.success('{{ addslashes(session('success')) }}');
                } else {
                    // Réessayer après 200ms si pas encore chargé
                    setTimeout(afficherAlerteSucces, 200);
                }
            }
            
            // Démarrer dès que possible
            if (document.readyState === 'loading') {
                document.addEventListener('DOMContentLoaded', afficherAlerteSucces);
            } else {
                setTimeout(afficherAlerteSucces, 100);
            }
        </script>
    </div>
@endif

@if(session('error'))
    <div class="mb-6">
        <script>
            function afficherAlerteErreur() {
                if (typeof alerteModerne !== 'undefined' && alerteModerne) {
                    alerteModerne.error('{{ addslashes(session('error')) }}');
                } else {
                    setTimeout(afficherAlerteErreur, 200);
                }
            }
            
            if (document.readyState === 'loading') {
                document.addEventListener('DOMContentLoaded', afficherAlerteErreur);
            } else {
                setTimeout(afficherAlerteErreur, 100);
            }
        </script>
    </div>
@endif

@if(session('warning'))
    <div class="mb-6">
        <script>
            function afficherAlerteAvertissement() {
                if (typeof alerteModerne !== 'undefined' && alerteModerne) {
                    alerteModerne.warning('{{ addslashes(session('warning')) }}');
                } else {
                    setTimeout(afficherAlerteAvertissement, 200);
                }
            }
            
            if (document.readyState === 'loading') {
                document.addEventListener('DOMContentLoaded', afficherAlerteAvertissement);
            } else {
                setTimeout(afficherAlerteAvertissement, 100);
            }
        </script>
    </div>
@endif

@if(session('info'))
    <div class="mb-6">
        <script>
            function afficherAlerteInfo() {
                if (typeof alerteModerne !== 'undefined' && alerteModerne) {
                    alerteModerne.info('{{ addslashes(session('info')) }}');
                } else {
                    setTimeout(afficherAlerteInfo, 200);
                }
            }
            
            if (document.readyState === 'loading') {
                document.addEventListener('DOMContentLoaded', afficherAlerteInfo);
            } else {
                setTimeout(afficherAlerteInfo, 100);
            }
        </script>
    </div>
@endif

@if($errors->any())
    <div class="mb-6">
        <script>
            function afficherErreursValidation() {
                if (typeof alerteModerne !== 'undefined' && alerteModerne) {
                    const erreurs = @json($errors->all());
                    const message = 'Erreurs de validation:\\n' + erreurs.join('\\n');
                    alerteModerne.error(message);
                } else {
                    setTimeout(afficherErreursValidation, 200);
                }
            }
            
            if (document.readyState === 'loading') {
                document.addEventListener('DOMContentLoaded', afficherErreursValidation);
            } else {
                setTimeout(afficherErreursValidation, 100);
            }
        </script>
    </div>
@endif