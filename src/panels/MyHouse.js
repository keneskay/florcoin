/* eslint-disable */
import React from 'react';
import PropTypes from 'prop-types';
import { Panel, PanelHeader, HeaderButton, Button, platform, IOS, Group, Cell, List } from '@vkontakte/vkui';
import Icon28ChevronBack from '@vkontakte/icons/dist/28/chevron_back';
import Icon24Back from '@vkontakte/icons/dist/24/back';

const osname = platform();

const MyHouse = props => (
    <Panel id={props.id}>
        <PanelHeader
            left={<HeaderButton onClick={props.go} data-to="home">
                {osname === IOS ? <Icon28ChevronBack /> : <Icon24Back />}
            </HeaderButton>}
        >
            Дома
		</PanelHeader>
        <Group title="Дом">
            <List>
                <Cell
                size="l"
                description="Вы можете сдать свой дом в аренду, а так же продать."
                bottomContent={
                    <div style={{ display: 'flex' }}>
                        <Button size="m" onClick={props.pohod}>Сдать в аренду</Button>
                        <Button size="m" level="secondary" style={{ marginLeft: 8 }} onClick={() => props.sell(`${props.userAcc.house}`)}>Продать</Button>
                    </div>
                } 
                >
                    {props.userAcc.house_name}
                </Cell>
            </List>
        </Group>
    </Panel>
);

MyHouse.propTypes = {
    id: PropTypes.string.isRequired,
    go: PropTypes.func.isRequired,
};

export default MyHouse;
