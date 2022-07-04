/* eslint-disable */
import React from 'react';
import PropTypes from 'prop-types';
import { Panel, PanelHeader, HeaderButton, platform, IOS, Group, Cell, List, Tabs, TabsItem, HorizontalScroll, Avatar } from '@vkontakte/vkui';
import Icon28ChevronBack from '@vkontakte/icons/dist/28/chevron_back';
import Icon24Back from '@vkontakte/icons/dist/24/back';

const osname = platform();

function GetItemClicks(props) {
    return (
        <Cell asideContent={<h2 className="mr-2">{Number(props.pos) + 1}</h2>} before={
            <Avatar src={props.users.photo} size={60}/>} 
        description= {`${parseFloat(props.users.clicks).toFixed(3)} `} >
            <a href={"https://vk.com/id" +props.users.user_vk_id} > {`${props.users.first_name} ${props.users.last_name}`}</a></Cell>
    )
}

function GetItemReputation(props) {
    return (
        <Cell before={<h2 className="mr-2">{Number(props.pos) + 1}</h2>} 
        description={`${props.users.reputation} репутации`}>
        {`${props.users.first_name} ${props.users.last_name}`}</Cell>
    )
}

function SetCellClicks(props) {
    let users = props.users;
    // let my = users[users.length - 1];
    // users.splice(-1, 1);
    // console.log('users', users);
    // console.log('my', my);
    const cells = users.map((e, index) =>
        <GetItemClicks users={e} pos={index} />
    );
    return (<List>{cells}</List>);
}

function SetCellReputation(props) {
    const cells = props.users.map((e, index) =>
        <GetItemReputation users={e} pos={index} />
    );
    return (<List>{cells}</List>);
}

function TopTab(props) {
    const active = props.activeTab;
    if (active === 'click') {
        return (
            <Group title="Топ по коинам">
                <SetCellClicks users={props.usersTopClicks} />
            </Group>
        );
    } else if (active === 'rating') {
        return (
            <Group title="Топ по репутации">
                <SetCellReputation users={props.usersTopReputation} />
            </Group>
        )
    }
}

const Top = props => (
    <Panel id={props.id}>
        <PanelHeader
            left={<HeaderButton onClick={props.go} data-to="home">
                {osname === IOS ? <Icon28ChevronBack /> : <Icon24Back />}
            </HeaderButton>}
        >
            Топ
        </PanelHeader>
        <TopTab activeTab={props.activeTab} usersTopClicks={props.usersTopClicks} usersTopReputation={props.usersTopReputation} />
    </Panel>
);

Top.propTypes = {
    id: PropTypes.string.isRequired,
    go: PropTypes.func.isRequired,
};

export default Top;


