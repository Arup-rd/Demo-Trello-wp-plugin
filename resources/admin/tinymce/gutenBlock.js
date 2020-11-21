/*eslint-disable*/
const { __ } = wp.i18n;
const { registerBlockType } = wp.blocks;
const { SelectControl } = wp.components;

registerBlockType('my-plugin/guten-block', {
    title: __('My Plugin'),
    icon: (
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 321.98 249.25">
            <path
                class="A"
                d="M312.48 249.25H9.5a9.51 9.51 0 0 1-9.5-9.5V9.5A9.51 9.51 0 0 1 9.5 0h303a9.51 9.51 0 0 1 9.5 9.5v230.25a9.51 9.51 0 0 1-9.52 9.5zM9.5 7A2.53 2.53 0 0 0 7 9.5v230.25a2.53 2.53 0 0 0 2.5 2.5h303a2.53 2.53 0 0 0 2.5-2.5V9.5a2.53 2.53 0 0 0-2.5-2.5z"
            />
            <path class="A" d="M75 44.37h8.75v202.7H75z" />
            <path class="B" d="M129.37 44.37" />
            <path class="C" d="M249.37 44.37" />
            <path
                class="A"
                d="M6.16.5h309.66a6 6 0 0 1 6 6v43.8a.63.63 0 0 1-.63.63H.8a.63.63 0 0 1-.63-.63V6.5a6 6 0 0 1 6-6zM4.88 142.84h312.6v15.1H4.88zM22.47 90h28.27v16.97H22.47zm89.13 0h165.67v16.97H111.6zM22.47 190h28.27v16.97H22.47zm89.13 0h165.67v16.97H111.6z"
            />
        </svg>
    ),
    category: 'formatting',
    keywords: [
        __('My Plugin'),
        __('Gutenberg Block'),
        __('my-plugin-gutenberg-block')
    ],
    attributes: {
        tableId: {
            type: 'string'
        }
    },
    edit({ attributes, setAttributes }) {
        const config = window.my_plugin_tiny_mce;

        return (
            <div className="my-plugin-guten-wrapper">
                <div className="my-plugin-logo">
                    <img src={config.logo} alt="my-plugin-logo" />
                </div>

                <SelectControl
                    label={__('Select a Chart')}
                    value={attributes.tableId}
                    options={config.data.map((table) => ({
                        value: table.value,
                        label: table.text
                    }))}
                    onChange={(tableId) => setAttributes({ tableId })}
                />
            </div>
        );
    },
    save({ attributes }) {
        return '[my_plugin id="' + attributes.tableId + '"]';
    }
});
