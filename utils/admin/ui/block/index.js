import { registerPlugin } from '@wordpress/plugins';
import { PluginDocumentSettingPanel } from '@wordpress/editor';

import { useState } from 'react';
import { MenuGroup, MenuItemsChoice } from '@wordpress/components';

const MyMenuItemsChoice = () => {
    const [mode, setMode] = useState('default');
    const choices = [
        {
            value: 'performance',
            label: 'Performance Mode',
        },
        {
            value: 'default',
            label: 'Default Mode',
        },
    ];

    return (
        <MenuGroup label={"selected mode: " + mode}>
            <MenuItemsChoice
                choices={choices}
                value={mode}
                onSelect={(newMode) => setMode(newMode)}
            />
        </MenuGroup >
    );
};

const SettingsPanel = () => (
    <PluginDocumentSettingPanel
        name="css-optimiser-sidebar"
        title="CSS Optimisation"
        className="css-optimiser-sidebar"
    >

        <p>Select optimisation profile</p>
        <MyMenuItemsChoice />
        {/* USEFUL COMMAND TO GET CURRENT METADATA:   wp.data.select( 'core/editor' ).getCurrentPost().meta;     */}
        {/* <Button variant="primary">Click Me!</Button> */}


    </PluginDocumentSettingPanel>
);

registerPlugin('css-optimise-setting-panel', {
    render: SettingsPanel,
    icon: 'palmtree',
});
