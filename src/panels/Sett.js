/* eslint-disable */
import React from 'react';
import PropTypes from 'prop-types';
import {Panel, PanelHeader, HeaderButton, platform, IOS, Cell, List, Group} from '@vkontakte/vkui';
import Icon28ChevronBack from '@vkontakte/icons/dist/28/chevron_back';
import Icon24Back from '@vkontakte/icons/dist/24/back';

const osname = platform();

const Sett = ({ dark, light, go }) => (
	<Panel id="sett">
		<PanelHeader
			left={<HeaderButton onClick={go} data-to="dop">
				{osname === IOS ? <Icon28ChevronBack/> : <Icon24Back/>}
			</HeaderButton>}
		>
			Темы		
		</PanelHeader>
        <Group title="Смена темы">
		<List>
							<Cell onClick={dark}>
								Темная
							</Cell>	
                            <Cell onClick={light}>
								Стандартная
							</Cell>	
						</List>
                        </Group>
	</Panel>
);
Sett.propTypes = {
	id: PropTypes.string.isRequired,
	go: PropTypes.func.isRequired,
};

export default Sett;
