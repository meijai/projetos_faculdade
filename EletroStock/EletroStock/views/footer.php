                    <!-- Rodapé do v-dashboard -->
                    <footer class="mt-12 pt-6 border-t border-gray-300 text-center text-xs text-gray-500">
                        <p class="font-medium">EletroStock &bull; Sistema de Gestão de Materiais Elétricos</p>
                        <p class="mt-1">Integrantes: Bruno Furlan &bull; Higor Afonso Kleemann &bull; Lucas Castanheiro &bull; Lucas Piva Bellaver</p>
                    </footer>
                </div>
            </main>
        </div>
    </div>

    <!-- Scripts de Interatividade do Template v-dashboard -->
    <script>
        function toggleSidebar() {
            const sidebar = document.getElementById('sidebar');
            const backdrop = document.getElementById('sidebar-backdrop');
            if (sidebar.classList.contains('-translate-x-full')) {
                sidebar.classList.remove('-translate-x-full');
                sidebar.classList.add('translate-x-0');
                backdrop.classList.remove('hidden');
            } else {
                sidebar.classList.add('-translate-x-full');
                sidebar.classList.remove('translate-x-0');
                backdrop.classList.add('hidden');
            }
        }

        // Filtro em tempo real para as tabelas da página ativa
        function handleGlobalSearch(e) {
            const term = (e.target.value || '').toLowerCase().trim();
            const tables = document.querySelectorAll('table tbody');
            tables.forEach(tbody => {
                const rows = tbody.querySelectorAll('tr');
                rows.forEach(row => {
                    const text = row.innerText.toLowerCase();
                    row.style.display = text.includes(term) ? '' : 'none';
                });
            });
        }
    </script>
</body>
</html>
