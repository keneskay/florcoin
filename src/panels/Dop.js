/* eslint-disable */
import React from 'react';
import PropTypes from 'prop-types';
import {Panel, PanelHeader, HeaderButton, platform, IOS, Cell, List} from '@vkontakte/vkui';
import Icon28ChevronBack from '@vkontakte/icons/dist/28/chevron_back';
import Icon24Back from '@vkontakte/icons/dist/24/back';
import Icon24Settings from '@vkontakte/icons/dist/24/settings';
import Icon24Share from '@vkontakte/icons/dist/24/share';

const osname = platform();

const Dop = props => (
<Panel id={props.id}>
		<PanelHeader
			left={<HeaderButton onClick={props.go} data-to="home">
				{osname === IOS ? <Icon28ChevronBack/> : <Icon24Back/>}
			</HeaderButton>}
		>
			Дополнительно		
		</PanelHeader>
		<List>
            <Cell before={<Icon24Share className="CellIconCustom" />} onClick={props.go} data-to="Transfer">
                Переводы
            </Cell>	
        </List>
	</Panel>
);

Dop.propTypes = {
	id: PropTypes.string.isRequired,
	go: PropTypes.func.isRequired,
};

export default Dop;
