    <style>
        .article-quill .ql-toolbar.ql-snow {
            border-top-left-radius: 1.5rem;
            border-top-right-radius: 1.5rem;
            border-color: #e2e8f0;
            background-color: #f8fafc;
            padding: 1rem;
        }

        .article-quill .ql-container.ql-snow {
            border-bottom-left-radius: 1.5rem;
            border-bottom-right-radius: 1.5rem;
            border-color: #e2e8f0;
            min-height: 400px;
        }

        .article-quill .ql-editor {
            min-height: 400px;
            padding: 2rem;
            font-size: 1.125rem;
            line-height: 1.75;
            color: #334155;
        }

        .article-quill .ql-editor.ql-blank::before {
            color: #94a3b8;
            font-style: normaal;
            left: 2rem;
        }

        .article-quill .ql-size-large {
            font-size: 1.3em;
        }

        .article-quill .ql-size-huge {
            font-size: 1.8em;
        }

        .article-quill--compact .ql-toolbar.ql-snow {
            border-width: 0;
            padding-bottom: 0;
        }

        .article-quill--compact .ql-editor {
            padding: 1rem 1.25rem 1.1rem;
            font-size: 1.125rem;
        }
    </style>
    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('quillArticleEditor', (value, options = {}) => ({
                value,
                options,
                quill: null,
                lastRange: null,
                syncingFromEditor: false,
                captionModalOpen: false,
                captionText: '',
                activeImageNode: null,
                init() {
                    if (!window.Quill) {
                        console.error('Quill failed to load from the Vite bundle.');

                        return;
                    }

                    this.quill = new window.Quill(this.$refs.editor, {
                        theme: 'snow',
                        bounds: this.$refs.editor,
                        placeholder: this.options.placeholder ?? '',
                        modules: {
                            toolbar: {
                                container: this.toolbarOptions(),
                                handlers: {
                                    image: () => this.openImagePicker(),
                                },
                            },
                        },
                    });

                    this.setEditorHtml(this.value ?? '');
                    this.registerImageInteractions();

                    this.quill.on('selection-change', (range) => {
                        if (range) {
                            this.lastRange = range;
                        }
                    });

                    this.quill.on('text-change', (_delta, _oldDelta, source) => {
                        if (source !== 'user') {
                            return;
                        }

                        this.syncingFromEditor = true;
                        this.value = this.currentHtml();
                        this.syncingFromEditor = false;
                    });

                    this.$watch('value', (nextValue) => {
                        if (this.syncingFromEditor) {
                            return;
                        }

                        if (this.quill.hasFocus()) {
                            return; // Jangan override editor ketika user sedang mengetik
                        }

                        if (this.currentHtml() === this.normalizeHtml(nextValue ?? '')) {
                            return;
                        }

                        this.setEditorHtml(nextValue ?? '');
                    });
                },
                toolbarOptions() {
                    if (this.options.compact) {
                        return [
                            [{
                                font: ['sans', 'serif', 'mono', 'display']
                            }],
                            [{
                                size: ['small', false, 'large', 'huge']
                            }],
                            [{
                                header: [2, 3, false]
                            }],
                            ['bold', 'italic', 'underline'],
                            [{
                                color: []
                            }, {
                                background: []
                            }],
                            [{
                                align: []
                            }],
                            [{
                                list: 'ordered'
                            }, {
                                list: 'bullet'
                            }],
                            ['blockquote', 'link', 'image'],
                            ['clean'],
                        ];
                    }

                    return [
                        [{
                            font: ['sans', 'serif', 'mono', 'display']
                        }],
                        [{
                            size: ['small', false, 'large', 'huge']
                        }],
                        [{
                            header: [1, 2, 3, false]
                        }],
                        ['bold', 'italic', 'underline', 'strike'],
                        [{
                            color: []
                        }, {
                            background: []
                        }],
                        [{
                            align: []
                        }],
                        [{
                            list: 'ordered'
                        }, {
                            list: 'bullet'
                        }],
                        ['blockquote', 'code-block'],
                        ['link', 'image'],
                        ['clean'],
                    ];
                },
                normalizeHtml(html) {
                    const normalized = (html ?? '').trim();

                    if (normalized === '' || normalized === '<p><br></p>' || normalized ===
                        '<div><br></div>') {
                        return '';
                    }

                    return normalized;
                },
                currentHtml() {
                    return this.normalizeHtml(this.quill?.root?.innerHTML ?? '');
                },
                setEditorHtml(html) {
                    const normalized = this.normalizeHtml(html);

                    if (normalized === '') {
                        this.quill.setText('\n', 'silent');

                        return;
                    }

                    const delta = this.quill.clipboard.convert({
                        html: normalized
                    });
                    this.quill.setContents(delta, 'silent');
                },
                registerImageInteractions() {
                    this.quill.root.addEventListener('drop', async (event) => {
                        const files = Array.from(event.dataTransfer?.files ?? []).filter((
                            file) => file.type.startsWith('image/'));

                        if (!files.length) {
                            return;
                        }

                        event.preventDefault();
                        await this.insertImages(files);
                    });

                    this.quill.root.addEventListener('paste', async (event) => {
                        const files = Array.from(event.clipboardData?.files ?? []).filter((
                            file) => file.type.startsWith('image/'));

                        if (!files.length) {
                            return;
                        }

                        event.preventDefault();
                        await this.insertImages(files);
                    });

                    // Tambahkan double-click listener untuk fitur caption gambar menggunakan custom modal
                    this.quill.root.addEventListener('dblclick', (event) => {
                        if (event.target.tagName === 'IMG') {
                            this.activeImageNode = event.target;
                            this.captionText = this.activeImageNode.getAttribute('data-caption') || this.activeImageNode.getAttribute('alt') || '';
                            this.captionModalOpen = true;
                            
                            setTimeout(() => {
                                if (this.$refs.captionInput) this.$refs.captionInput.focus();
                            }, 100); // Tunggu modal render
                        }
                    });
                },
                saveCaption() {
                    if (this.activeImageNode) {
                        const caption = this.captionText.trim();
                        if (caption === '') {
                            this.activeImageNode.removeAttribute('alt');
                            this.activeImageNode.removeAttribute('title');
                            this.activeImageNode.removeAttribute('data-caption');
                        } else {
                            this.activeImageNode.setAttribute('alt', caption);
                            this.activeImageNode.setAttribute('title', caption);
                            this.activeImageNode.setAttribute('data-caption', caption);
                        }
                        
                        this.syncingFromEditor = true;
                        this.value = this.currentHtml();
                        this.syncingFromEditor = false;
                    }
                    this.closeCaptionModal();
                },
                closeCaptionModal() {
                    this.captionModalOpen = false;
                    this.activeImageNode = null;
                    this.captionText = '';
                },
                openImagePicker() {
                    const input = document.createElement('input');
                    input.type = 'file';
                    input.accept = 'image/*';
                    input.addEventListener('change', async () => {
                        const files = Array.from(input.files ?? []);

                        if (!files.length) {
                            return;
                        }

                        await this.insertImages(files);
                    }, {
                        once: true
                    });

                    input.click();
                },
                async insertImages(files) {
                    for (const file of files) {
                        const imageUrl = await this.readFileAsDataUrl(file);
                        const range = this.safeRange();

                        // Gunakan insertEmbed untuk Quill 2
                        this.quill.insertEmbed(range.index, 'image', imageUrl, 'user');

                        const nextIndex = range.index + 1;
                        this.quill.setSelection(nextIndex, 0, 'silent');
                        this.lastRange = {
                            index: nextIndex,
                            length: 0
                        };
                    }
                },
                safeRange() {
                    if (this.lastRange) {
                        return this.lastRange;
                    }

                    const length = Math.max(this.quill.getLength() - 1, 0);

                    return {
                        index: length,
                        length: 0
                    };
                },
                escapeHtml(value) {
                    return String(value)
                        .replaceAll('&', '&amp;')
                        .replaceAll('<', '&lt;')
                        .replaceAll('>', '&gt;')
                        .replaceAll('"', '&quot;')
                        .replaceAll("'", '&#039;');
                },
                readFileAsDataUrl(file) {
                    return new Promise((resolve, reject) => {
                        const reader = new FileReader();

                        reader.onload = () => resolve(reader.result);
                        reader.onerror = () => reject(reader.error);

                        reader.readAsDataURL(file);
                    });
                },
            }));
        });
    </script>
