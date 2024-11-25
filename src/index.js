import { registerPlugin } from '@wordpress/plugins';
import { PluginDocumentSettingPanel } from '@wordpress/editor';
import { Button } from '@wordpress/components';


const PluginDocumentSettingPanelDemo = () => (
    <PluginDocumentSettingPanel
        name="css-optimiser-sidebar"
        title="CSS Optimisation"
        className="css-optimiser-sidebar"
    >

        <p>Custom Document Setting Panel</p>

        {/* USEFUL COMMAND TO GET CURRENT METADATA:   wp.data.select( 'core/editor' ).getCurrentPost().meta;     */}
        <Button variant="primary">Click Me!</Button>


    </PluginDocumentSettingPanel>
);

registerPlugin('plugin-document-setting-panel-demo', {
    render: PluginDocumentSettingPanelDemo,
    icon: 'palmtree',
});