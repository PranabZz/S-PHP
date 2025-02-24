<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Advanced Rich Text Editor</title>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/tinymce/6.4.2/tinymce.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/tailwindcss/2.2.19/tailwind.min.css">
    <style>
        body {
            font-family: 'Inter', sans-serif;
        }

        .editor-container {
            background: white;
            border-radius: 0.5rem;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        .sidebar {
            width: 300px;
            transition: all 0.3s;
        }

        .sidebar.collapsed {
            width: 60px;
        }

        .main-content {
            transition: all 0.3s;
        }

        .tox-tinymce {
            border-radius: 0.5rem !important;
            border: none !important;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05) !important;
        }

        .tox-statusbar {
            border-top: 1px solid #eee !important;
        }

        .word-count {
            padding: 0.5rem;
            background: #f8f9fa;
            border-radius: 0.25rem;
            font-size: 0.875rem;
        }

        .status-indicator {
            width: 10px;
            height: 10px;
            border-radius: 50%;
            display: inline-block;
            margin-right: 5px;
        }

        .status-draft {
            background-color: #f59e0b;
        }

        .status-published {
            background-color: #10b981;
        }

        .status-scheduled {
            background-color: #6366f1;
        }
    </style>
</head>

<body class="bg-gray-100 min-h-screen">
    <div class="container mx-auto py-8 px-4">
        <div class="flex flex-col lg:flex-row gap-6">
            <!-- Left Sidebar -->
            <div class="sidebar bg-white p-6 rounded-lg shadow-lg">
                <!-- Sidebar Header -->
                <div class="flex justify-between items-center mb-6">
                    <h2 class="text-2xl font-semibold text-gray-800">Post</h2>
                    <button id="collapse-sidebar" class="text-gray-500 hover:text-gray-700">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M11 19l-7-7 7-7m8 14l-7-7 7-7" />
                        </svg>
                    </button>
                </div>

                <!-- Content Type Selection -->
                <div class="mb-6">
                    <label for="content-type" class="block text-sm font-medium text-gray-700 mb-2">Select Content
                        Type</label>
                    <select id="content-type"
                        class="w-full p-3 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="blog">Blog</option>
                        <option value="widget">Page Widget</option>
                    </select>
                </div>

                <!-- Tabs Navigation -->
                <div class="mb-6">
                    <ul class="flex space-x-6 border-b border-gray-300">
                        <li><a href="#post-settings"
                                class="tab-btn text-sm px-4 py-2 text-gray-700 hover:text-blue-500 border-b-2 border-transparent hover:border-blue-500">Post
                            </a></li>
                        <li><a href="#seo-settings"
                                class="tab-btn text-sm px-4 py-2 text-gray-700 hover:text-blue-500 border-b-2 border-transparent hover:border-blue-500">SEO
                            </a></li>
                        <li><a href="#publish-settings"
                                class="tab-btn text-sm px-4 py-2 text-gray-700 hover:text-blue-500 border-b-2 border-transparent hover:border-blue-500">Publish
                            </a></li>
                    </ul>
                </div>

                <!-- Tabs Content -->
                <div id="post-settings" class="tab-content mb-6">
                    <!-- Post/Widget Settings -->
                    <div id="blog-settings" class="content-type-settings mb-6">
                        <label for="post-title" class="block text-sm font-medium text-gray-700 mb-2">Post Title</label>
                        <input type="text" id="post-title"
                            class="w-full p-3 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                            placeholder="Enter post title...">
                    </div>

                    <div id="widget-settings" class="content-type-settings hidden mb-6">
                        <label for="widget-title" class="block text-sm font-medium text-gray-700 mb-2">Widget
                            Title</label>
                        <input type="text" id="widget-title"
                            class="w-full p-3 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                            placeholder="Enter widget title...">
                    </div>

                    <!-- Featured Image Section -->
                    <div class="mb-6">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Featured Image</label>
                        <div
                            class="border-2 border-dashed border-gray-300 rounded-md p-6 text-center hover:bg-gray-50 transition">
                            <svg xmlns="http://www.w3.org/2000/svg" class="mx-auto h-12 w-12 text-gray-400" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                            <p class="mt-1 text-sm text-gray-500">Drag and drop or click to upload</p>
                            <input type="file" class="hidden" id="featured-image">
                            <button
                                class="mt-2 px-3 py-1 bg-gray-200 rounded-md text-sm hover:bg-gray-300 transition">Select
                                File</button>
                        </div>
                    </div>

                    <!-- Categories Section -->
                    <div class="mb-6">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Categories</label>
                        <div class="flex flex-wrap gap-2">
                            <span class="px-3 py-1 bg-blue-100 text-blue-800 rounded-full text-sm flex items-center">
                                Technology
                                <button class="ml-1 text-blue-600 hover:text-blue-800">×</button>
                            </span>
                            <span class="px-3 py-1 bg-green-100 text-green-800 rounded-full text-sm flex items-center">
                                Design
                                <button class="ml-1 text-green-600 hover:text-green-800">×</button>
                            </span>
                            <button class="px-3 py-1 bg-gray-200 rounded-full text-sm hover:bg-gray-300 transition">+
                                Add</button>
                        </div>
                    </div>
                </div>

                <!-- SEO Settings -->
                <div id="seo-settings" class="tab-content hidden mb-6">
                    <div class="mb-6">
                        <label for="seo-title" class="block text-sm font-medium text-gray-700 mb-2">SEO Title</label>
                        <input type="text" id="seo-title"
                            class="w-full p-3 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                            placeholder="Enter SEO title...">
                    </div>

                    <div class="mb-6">
                        <label for="meta-description" class="block text-sm font-medium text-gray-700 mb-2">Meta
                            Description</label>
                        <textarea id="meta-description" rows="4"
                            class="w-full p-3 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                            placeholder="Enter meta description..."></textarea>
                    </div>

                    <div class="mb-6">
                        <label for="keywords" class="block text-sm font-medium text-gray-700 mb-2">SEO Keywords</label>
                        <input type="text" id="keywords"
                            class="w-full p-3 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                            placeholder="Enter SEO keywords...">
                    </div>
                </div>

                <!-- Publish Settings -->
                <div id="publish-settings" class="tab-content hidden mb-6">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Publish Settings</label>
                    <div class="space-y-2">
                        <div class="flex items-start">
                            <input type="radio" id="publish-now" name="publish-setting" class="mt-1 mr-2" checked>
                            <label for="publish-now" class="text-sm">Publish now</label>
                        </div>
                        <div class="flex items-start">
                            <input type="radio" id="schedule" name="publish-setting" class="mt-1 mr-2">
                            <label for="schedule" class="text-sm">Schedule</label>
                        </div>
                        <div class="flex items-start">
                            <input type="radio" id="save-draft" name="publish-setting" class="mt-1 mr-2">
                            <label for="save-draft" class="text-sm">Save as draft</label>
                        </div>
                    </div>
                </div>

                <div class="pt-4 border-t border-gray-200">

                    <button
                        class="w-full py-3 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition font-medium">
                        Publish Post
                    </button>
                    <button
                        class="w-full mt-2 py-3 bg-white border border-gray-300 text-gray-700 rounded-md hover:bg-gray-50 transition font-medium">
                        Save Draft
                    </button>
                </div>
            </div>

            <!-- Main Content Area -->
            <div class="main-content flex-1">
                <div class="bg-white rounded-lg shadow mb-6 p-4">
                    <div class="flex justify-between items-center">
                        <div class="flex items-center space-x-2">
                            <h1 id="displayed-title" class="text-2xl font-bold">Untitled Post</h1>
                            <span class="status-indicator status-draft"></span>
                            <span class="text-sm text-gray-500">Draft</span>
                        </div>
                        <div class="flex items-center space-x-4">
                            <div class="word-count flex items-center">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1 text-gray-500" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z" />
                                </svg>
                                <span id="word-count">0 words</span>
                            </div>
                            <div class="hidden md:flex items-center text-sm text-gray-500">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                Last saved: Just now
                            </div>
                            <div class="flex items-center space-x-2">
                                <button class="p-2 rounded-md hover:bg-gray-100 transition" title="Preview">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-600" fill="none"
                                        viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                    </svg>
                                </button>
                                <button class="p-2 rounded-md hover:bg-gray-100 transition" title="Settings">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-600" fill="none"
                                        viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                    </svg>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="editor-container">
                    <textarea id="editor"></textarea>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Initialize TinyMCE
            tinymce.init({
                selector: '#editor',
                plugins: 'preview importcss searchreplace autolink autosave save directionality code visualblocks visualchars fullscreen image link media template codesample table charmap pagebreak nonbreaking anchor insertdatetime advlist lists wordcount help charmap quickbars emoticons',
                menubar: 'file edit view insert format tools table help',
                toolbar: 'undo redo | bold italic underline strikethrough | fontfamily fontsize blocks | alignleft aligncenter alignright alignjustify | outdent indent |  numlist bullist | forecolor backcolor removeformat | pagebreak | charmap emoticons | fullscreen  preview save print | insertfile image media template link anchor codesample | ltr rtl',
                toolbar_sticky: true,
                autosave_ask_before_unload: true,
                autosave_interval: '30s',
                autosave_prefix: '{path}{query}-{id}-',
                autosave_restore_when_empty: false,
                autosave_retention: '2m',
                image_advtab: true,
                link_list: [
                    { title: 'My page 1', value: 'https://www.example.com' },
                    { title: 'My page 2', value: 'http://www.example.com' }
                ],
                image_list: [
                    { title: 'My image 1', value: 'https://www.example.com/image1.jpg' },
                    { title: 'My image 2', value: 'http://www.example.com/image2.jpg' }
                ],
                image_class_list: [
                    { title: 'None', value: '' },
                    { title: 'Responsive', value: 'img-fluid' }
                ],
                importcss_append: true,
                height: 600,
                templates: [
                    { title: 'New Article', description: 'Creates a new article', content: '<div class="article"><h2>Article Title</h2><p>Article content goes here...</p></div>' },
                    { title: 'Product Review', description: 'Creates a product review template', content: '<div class="review"><h2>Product Name</h2><div class="rating">★★★★☆</div><p>Review content goes here...</p></div>' }
                ],
                template_cdate_format: '[CDATE: %m/%d/%Y]',
                template_mdate_format: '[MDATE: %m/%d/%Y]',
                image_caption: true,
                quickbars_selection_toolbar: 'bold italic | quicklink h2 h3 blockquote quickimage quicktable',
                noneditable_class: 'mceNonEditable',
                toolbar_mode: 'sliding',
                contextmenu: 'link image table',
                skin: 'oxide',
                content_css: 'default',
                content_style: 'body { font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Helvetica, Arial, sans-serif; font-size: 16px; line-height: 1.6; }',
                setup: function (editor) {
                    editor.on('KeyUp', function (e) {
                        updateWordCount(editor);
                    });
                }
            });

            // Handle word count
            function updateWordCount(editor) {
                const content = editor.getContent({ format: 'text' });
                const wordCount = content.split(/\s+/).filter(word => word.length > 0).length;
                document.getElementById('word-count').textContent = wordCount + ' words';
            }

            // Handle post title sync
            const postTitleInput = document.getElementById('post-title');
            const displayedTitle = document.getElementById('displayed-title');

            postTitleInput.addEventListener('input', function () {
                const title = postTitleInput.value.trim() || 'Untitled Post';
                displayedTitle.textContent = title;
            });

            // Handle sidebar collapse
            const collapseSidebarBtn = document.getElementById('collapse-sidebar');
            const sidebar = document.querySelector('.sidebar');
            const sidebarContent = document.querySelector('.sidebar-content');
            const mainContent = document.querySelector('.main-content');

            collapseSidebarBtn.addEventListener('click', function () {
                sidebar.classList.toggle('collapsed');
                if (sidebar.classList.contains('collapsed')) {
                    sidebarContent.style.display = 'none';
                    collapseSidebarBtn.innerHTML = '<svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 5l7 7-7 7M5 5l7 7-7 7" /></svg>';
                } else {
                    sidebarContent.style.display = 'block';
                    collapseSidebarBtn.innerHTML = '<svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 19l-7-7 7-7m8 14l-7-7 7-7" /></svg>';
                }
            });
        });
    </script>
    <script>
        const tabBtns = document.querySelectorAll('.tab-btn');
        const tabContents = document.querySelectorAll('.tab-content');

        tabBtns.forEach((tab, index) => {
            tab.addEventListener('click', () => {
                // Hide all tab contents
                tabContents.forEach(content => content.classList.add('hidden'));
                // Remove active class from all tabs
                tabBtns.forEach(btn => btn.classList.remove('border-blue-500', 'text-blue-500'));
                // Show selected tab content
                tabContents[index].classList.remove('hidden');
                // Add active class to the clicked tab
                tab.classList.add('border-blue-500', 'text-blue-500');
            });
        });

        // Initially show Post Settings tab
        tabBtns[0].classList.add('border-blue-500', 'text-blue-500');
        tabContents[0].classList.remove('hidden');

        document.getElementById('content-type').addEventListener('change', function () {
            const contentType = this.value;
            const blogSettings = document.getElementById('blog-settings');
            const widgetSettings = document.getElementById('widget-settings');

            if (contentType === 'blog') {
                blogSettings.classList.remove('hidden');
                widgetSettings.classList.add('hidden');
            } else {
                blogSettings.classList.add('hidden');
                widgetSettings.classList.remove('hidden');
            }
        });
    </script>
</body>

</html>