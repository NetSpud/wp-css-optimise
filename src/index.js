import { registerPlugin } from '@wordpress/plugins';
import { PluginDocumentSettingPanel } from '@wordpress/editor';

const PluginDocumentSettingPanelDemo = () => (
    <PluginDocumentSettingPanel
        name="css-optimiser-sidebar"
        title="CSS Optimisation"
        className="css-optimiser-sidebar"
    >

        <p>Custom Document Setting Panel</p>


    </PluginDocumentSettingPanel>
);

registerPlugin('plugin-document-setting-panel-demo', {
    render: PluginDocumentSettingPanelDemo,
    icon: 'palmtree',
});