(function (blocks, element, blockEditor) {
    const el = element.createElement;
    const { RichText, InnerBlocks } = blockEditor;

    // --- 1. PROJECT LIST BLOCK ---
    blocks.registerBlockType('gnn/project-list', {
        title: 'GNN Project List',
        icon: 'portfolio',
        category: 'layout',
        attributes: {
            content: { type: 'string', source: 'html', selector: 'div' }
        },
        edit: function (props) {
            return el('div', { className: 'gnn-block-editor project-list' },
                el('h3', {}, 'Project List (Brutalist Grid)'),
                el(RichText, {
                    tagName: 'div',
                    placeholder: 'Enter project details...',
                    value: props.attributes.content,
                    onChange: function (newContent) { props.setAttributes({ content: newContent }); }
                })
            );
        },
        save: function (props) {
            return el(RichText.Content, { tagName: 'div', value: props.attributes.content, className: 'gnn-project-list-container' });
        }
    });

    // --- 2. SERVICES BLOCK ---
    blocks.registerBlockType('gnn/services', {
        title: 'GNN Services',
        icon: 'grid-view',
        category: 'layout',
        attributes: {
            title: { type: 'string', source: 'html', selector: 'h2' },
            description: { type: 'string', source: 'html', selector: 'p' }
        },
        edit: function (props) {
            return el('div', { className: 'gnn-block-editor services' },
                el(RichText, {
                    tagName: 'h2',
                    placeholder: 'Service Title',
                    value: props.attributes.title,
                    onChange: function (v) { props.setAttributes({ title: v }); }
                }),
                el(RichText, {
                    tagName: 'p',
                    placeholder: 'Service Description',
                    value: props.attributes.description,
                    onChange: function (v) { props.setAttributes({ description: v }); }
                })
            );
        },
        save: function (props) {
            return el('div', { className: 'gnn-service-card' },
                el(RichText.Content, { tagName: 'h2', value: props.attributes.title }),
                el(RichText.Content, { tagName: 'p', value: props.attributes.description })
            );
        }
    });

    // --- 3. REFERENCES BLOCK ---
    blocks.registerBlockType('gnn/references', {
        title: 'GNN References',
        icon: 'awards',
        category: 'layout',
        edit: function () {
            return el('div', { className: 'gnn-block-editor references' }, 'References Section (Brutalist List)');
        },
        save: function () {
            return el('div', { className: 'gnn-references-list' }, el(InnerBlocks.Content));
        }
    });

})(window.wp.blocks, window.wp.element, window.wp.blockEditor);
