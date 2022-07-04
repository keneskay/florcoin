/* eslint-disable */
import React from 'react';
import PropTypes from 'prop-types';
import { Panel, PanelHeader, HeaderButton, platform, IOS, Cell, List, FormLayout, Button, Div, Input, FormLayoutGroup} from '@vkontakte/vkui';
import './Persik.css';
import Icon28ChevronBack from '@vkontakte/icons/dist/28/chevron_back';
import Icon24Back from '@vkontakte/icons/dist/24/back';
import Icon24Coins from '@vkontakte/icons/dist/24/coins';
import Icon24Favorite from '@vkontakte/icons/dist/24/favorite';

const osname = platform();
const Reit = ({ add2, clicks, reputation, go, handleReputationChange, handleReputationSubmit, calcRep}) => (
	<Panel id="reit">
		<PanelHeader
			left={<HeaderButton onClick={go} data-to="persik">
				{osname === IOS ? <Icon28ChevronBack/> : <Icon24Back/>}
			</HeaderButton>}
		>
			Купить рейтинг		
		</PanelHeader>
        <List>
            <Cell before={<Icon24Coins className="CellIconCustom" />} asideContent={<b>{clicks}</b>}>
                Баланс:
            </Cell>
            <Cell before={<Icon24Favorite className="CellIconCustom" />} asideContent={<b>{reputation}</b>}>
                Рейтинг:
            </Cell>
        </List>
        <FormLayout>
            <FormLayoutGroup top="Количество">
                <Input type="text" type="number" defaultValue={reputation} onChange={handleReputationChange} />
            </FormLayoutGroup>
        </FormLayout>
        <Div>Стоимость: {calcRep} коинов</Div>
        <Div>
            <Button size="xl" level="primary" onClick={handleReputationSubmit}>Купить рейтинг</Button>
        </Div>
	</Panel>
);

Reit.propTypes = {
	id: PropTypes.string.isRequired,
	go: PropTypes.func.isRequired,
};

export default Reit;
