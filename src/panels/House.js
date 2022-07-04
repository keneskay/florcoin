/* eslint-disable */
import React from 'react';
import PropTypes from 'prop-types';
import { Panel, PanelHeader, HeaderButton, Button, platform, IOS, Group, Cell, List, FormStatus, Div} from '@vkontakte/vkui';
import Icon28ChevronBack from '@vkontakte/icons/dist/28/chevron_back';
import Icon24Back from '@vkontakte/icons/dist/24/back';

const osname = platform();

const House = props => (
	<Panel id={props.id}>
		<PanelHeader
			left={<HeaderButton onClick={props.go} data-to="persik">
				{osname === IOS ? <Icon28ChevronBack/> : <Icon24Back/>}
			</HeaderButton>}
		>
			Дома		
		</PanelHeader>
		<Div>
		<FormStatus title="Обратите внимание" state="error">
        Дома можно сдавать в аренду, но раз в час. 
        </FormStatus> 
		</Div>
        <Group title="Покупка дома">
            <List>
                <Cell description="25.000 коинов" asideContent={<Button onClick={() => props.buy(1)}>Купить</Button>} >Старый домик в лесу</Cell>
                <Cell description="50.000 коинов" asideContent={<Button onClick={() => props.buy(2)}>Купить</Button>} >Домик возле поселения</Cell>
                <Cell description="165.000 коинов" asideContent={<Button onClick={() => props.buy(3)}>Купить</Button>} >Большой дом возле реки</Cell>
                <Cell description="245.000 коинов" asideContent={<Button onClick={() => props.buy(4)}>Купить</Button>} >Дом в городе</Cell>
                <Cell description="555.000 коинов" asideContent={<Button onClick={() => props.buy(5)}>Купить</Button>} >Дом с джакузи</Cell>
				<Cell description="785.000 коинов" asideContent={<Button onClick={() => props.buy(6)}>Купить</Button>} >Дом возле моря</Cell>
				<Cell description="925.000 коинов" asideContent={<Button onClick={() => props.buy(7)}>Купить</Button>} >Дом отдыха</Cell>
            </List>
        </Group>
	</Panel>
);

House.propTypes = {
	id: PropTypes.string.isRequired,
	go: PropTypes.func.isRequired,
};

export default House;
