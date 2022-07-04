/* eslint-disable */
import React from 'react';
import PropTypes from 'prop-types';
import { Panel, PanelHeader, HeaderButton, FormStatus, platform, IOS, Cell, List, FormLayout, Button, Div, Input, Footer, FormLayoutGroup } from '@vkontakte/vkui';
import './Persik.css';
import Icon28ChevronBack from '@vkontakte/icons/dist/28/chevron_back';
import Icon24Back from '@vkontakte/icons/dist/24/back';
import Icon24Add from '@vkontakte/icons/dist/24/add';
import Icon24User from '@vkontakte/icons/dist/24/user';
import Icon24MoneyTransfer from '@vkontakte/icons/dist/24/money_transfer';
import Icon24MoreVertical from '@vkontakte/icons/dist/24/more_vertical';
import Icon24Play from '@vkontakte/icons/dist/24/play';
import Icon24Repost from '@vkontakte/icons/dist/24/repost';

const osname = platform();

const Transfer = ({ go, handleTransferChange, fetchedUser, handleTransferSubmit, userAcc, clicks, viv, v1, v2, v3, v4, v5, v6, v7, v8, v9, v10, v_id1, v_id2, v_id3, v_id4, v_id5, v_id6, v_id7, v_id8, v_id9, v_id10, v_s1, v_s2, v_s3, v_s4, v_s5, v_s6, v_s7, v_s8, v_s9, v_s10, history, history1, history2, history3, history4, history5, history6, history7, history8, history9, history10, h_id1, h_id2, h_id3, h_id4, h_id5, h_id6, h_id7, h_id8, h_id9, h_id10, h_s1, h_s2, h_s3, h_s4, h_s5, h_s6, h_s7, h_s8, h_s9, h_s10 }) => (
    <Panel id="Transfer">
        <PanelHeader
            left={<HeaderButton onClick={go} data-to="home">
                {osname === IOS ? <Icon28ChevronBack /> : <Icon24Back />}
            </HeaderButton>}
        >
            Перевод

		</PanelHeader>
        <Div> 
        <FormStatus title="Обратите внимание" state="error">
        Для перевода нужно вводить ID ВКонтакте. 
        </FormStatus> 
        </Div> 
        <List>
        <Cell style={{background: "#481677"}} before={<Icon24MoneyTransfer className="CellIconCustom" />} asideContent={<b>{clicks}</b>}>
                 Баланс:
            </Cell>
            </List>
        <FormLayout>
        
            <FormLayoutGroup top="Введите ID">
                <Input type="text" type="number" name="toId" defaultValue="" onChange={handleTransferChange} />
            </FormLayoutGroup>
            <FormLayoutGroup top="Введите кол-во">
                <Input type="text" type="number" name="transferCoins" defaultValue="1" onChange={handleTransferChange} />
            </FormLayoutGroup>
        </FormLayout>
        <Div>
            <Button size="xl" level="primary" onClick={handleTransferSubmit}>Перевести</Button>
        </Div>

    </Panel>
);

Transfer.propTypes = {
    id: PropTypes.string.isRequired,
    go: PropTypes.func.isRequired,
    fetchedUser: PropTypes.shape({
        photo_200: PropTypes.string,
        first_name: PropTypes.string,
        last_name: PropTypes.string,
        city: PropTypes.shape({
            title: PropTypes.string,
        }),
    }),
};



export default Transfer;
