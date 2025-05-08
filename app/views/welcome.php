@layout('main', ['title' => 'Sphp'])

<body class="antialiased bg-white">
    <main class="flex-grow flex flex-col items-center justify-center px-4 py-16">
        <div class="text-center max-w-2xl mx-auto">

            <!-- Centered Image -->
            <div class="flex justify-center mb-8">
                <img src="<?= asset('img/logo.png') ?>" class="w-36 h-36" alt="Logo">
            </div>

            <h1 class="text-4xl font-bold text-gray-900 mb-6">Welcome to Sphp Framework</h1>
            <p class="text-xl text-gray-700 mb-12">Hope you enjoy learning backend</p>

            <div class="flex flex-wrap justify-center gap-4">
                <a href="https://github.com/sphp-framework"
                    class="bg-black text-white px-8 py-3 rounded-md hover:bg-gray-800 transition-colors">
                    GitHub
                </a>
                <a href="/docs"
                    class="bg-white text-black px-8 py-3 border-2 border-black rounded-md hover:bg-gray-100 transition-colors">
                    Documentation
                </a>
            </div>
        </div>
    </main>


    <footer class="mt-auto py-8 border-t border-gray-200">
        <div class="max-w-4xl mx-auto px-4 flex justify-center items-center space-x-8 text-gray-600">
            <a href="/contribute" class="hover:text-black transition-colors">Contribute</a>
            <span class="text-gray-300">|</span>
            <a href="/writerspace" class="hover:text-black transition-colors">Writerspace</a>
            <span class="text-gray-300">|</span>
            <a href="/docs" class="hover:text-black transition-colors">Docs</a>
        </div>
    </footer>
</body>

</html>