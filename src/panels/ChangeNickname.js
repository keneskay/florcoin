/* eslint-disable */
import React from 'react';
import PropTypes from 'prop-types';
import { Panel, PanelHeader, HeaderButton, platform, IOS, FormLayout, Button, Div, Input, FormLayoutGroup } from '@vkontakte/vkui';
import './Persik.css';
import Icon28ChevronBack from '@vkontakte/icons/dist/28/chevron_back';
import Icon24Back from '@vkontakte/icons/dist/24/back';

const osname = platform();

const ChangeNickname = ({ go, handleNickChange, handleNickSubmit, userAcc }) => (
    <Panel id="changeNickname">
        <PanelHeader
            left={<HeaderButton onClick={go} data-to="home">
                {osname === IOS ? <Icon28ChevronBack /> : <Icon24Back />}
            </HeaderButton>}
        >
            Смена ника
		</PanelHeader>
        <FormLayout>
            <FormLayoutGroup top="Новый ник">
                <Input type="text" defaultValue={userAcc && userAcc.nickname} onChange={handleNickChange} />
            </FormLayoutGroup>
        </FormLayout>
        <Div>
            <Button size="xl" level="primary" onClick={handleNickSubmit}>Сохранить</Button>
        </Div>
    </Panel>
);

ChangeNickname.propTypes = {
    id: PropTypes.string.isRequired,
    go: PropTypes.func.isRequired,
};

export default ChangeNickname;
