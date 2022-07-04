/* eslint-disable */
import React from 'react';
import PropTypes from 'prop-types';
import { Panel, FixedLayout, PanelHeader, HeaderButton, platform, IOS, Group, Cell, List, Tabs, TabsItem, HorizontalScroll, Avatar } from '@vkontakte/vkui';
import Icon28ChevronBack from '@vkontakte/icons/dist/28/chevron_back';
import Icon24Back from '@vkontakte/icons/dist/24/back';

const osname = platform();

function GetItemUsers(props) {
    return (
        <Cell asideContent={<h2 className="mr-2">{Number(props.pos) + 1}</h2>} before={
            <Avatar src={props.users.photo} size={60}/>} 
        description= {`${parseFloat(props.users.clicks).toFixed(6)} `} >
            <a href={"https://vk.com/id" +props.users.user_vk_id} > {`${props.users.first_name} ${props.users.last_name}`}</a></Cell>
    )
}

function GetItemGroups(props) {
    return (
        <Cell asideContent={<h2 className="mr-2">{Number(props.pos) + 1}</h2>} before={
            <Avatar src={props.groups.group_avatar} size={60}/>} 
        description= {`${parseFloat(props.groups.clicks).toFixed(6)} `} >
            <a href={"https://vk.com/" +props.groups.group_screen} > {`${props.groups.group_name}`}</a></Cell>
    )
}

function SetCellUsers(props) {
    let users = props.users;
    const cells = users.map((e, index) =>
        <GetItemUsers users={e} pos={index} />
    );
    return (<List>{cells}</List>);
}

function SetCellGroups(props) {
    const cells = props.groups.map((e, index) =>
        <GetItemGroups groups={e} pos={index} />
    );
    return (<List>{cells}</List>);
}

function TopTab(props) {
    const active = props.activeTab;
    if (active === 'users') {
        return (
            <Group title="Топ игроков">
                <SetCellUsers users={props.usersTop} />
            </Group>
        );
    } else if (active === 'groups') {
        return (
            <Group title="Топ групп">
                <SetCellGroups groups={props.groupsTop} />
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
        <FixedLayout vertical="top">
          <Tabs theme="header" type="buttons">
            <HorizontalScroll>
              <TabsItem
                onClick={() => props.setTab("users")}
                selected={props.activeTab === 'users'}
              >
                Игроки
              </TabsItem>
              <TabsItem
                onClick={() => props.setTab("groups")}
                selected={props.activeTab === 'groups'}
              >
                Группы
              </TabsItem>
            </HorizontalScroll>
          </Tabs>
        </FixedLayout>
        <TopTab activeTab={props.activeTab} usersTop={props.usersTop} groupsTop={props.groupsTop} />
    </Panel>
);

Top.propTypes = {
    id: PropTypes.string.isRequired,
    go: PropTypes.func.isRequired,
};

export default Top;


