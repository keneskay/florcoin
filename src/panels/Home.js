/* eslint-disable */
import React from 'react';
import PropTypes from 'prop-types';
import connect from '@vkontakte/vkui-connect';
import Icon24Gift from '@vkontakte/icons/dist/24/gift';
import Icon24Mention from '@vkontakte/icons/dist/24/mention';
import Icon24User from '@vkontakte/icons/dist/24/user';
import Icon24Coins from '@vkontakte/icons/dist/24/coins';
import Icon24Favorite from '@vkontakte/icons/dist/24/favorite';
import Icon24Bug from '@vkontakte/icons/dist/24/bug';
import Icon24Send from '@vkontakte/icons/dist/24/send';
import Icon24Game from '@vkontakte/icons/dist/24/game';
import Icon24Home from '@vkontakte/icons/dist/24/home';
import Icon24Services from '@vkontakte/icons/dist/24/services';
import Icon24BrowserForward from '@vkontakte/icons/dist/24/settings';
import './assets/css/bootstrap.css';
import './assets/css/style.css';
import '@vkontakte/vkui/dist/vkui.css';
import { Panel, ListItem, Button, Group, Div, Avatar, Cell, List , Alert} from '@vkontakte/vkui';

function MyPetIs(props) {
    const isPetIn = props.pet;
    if (isPetIn === "0") {
        return <Cell before={<Icon24Bug className="CellIconCustom" />} asideContent={<b>Питомец не куплен</b>} onClick={props.go} data-to="pet">Питомец</Cell>;
    }
    else {
        return <Cell before={<Icon24Bug className="CellIconCustom" />} asideContent={props.user && <b>{`${props.user.pet_name}`}</b>} onClick={props.go} data-to="myPet">Питомец</Cell>;
    }  
}

function MyHouseIs(props) {
    const isHouseIn = props.house;
    if (isHouseIn === "0") {
        return <Cell before={<Icon24Home className="CellIconCustom" />} asideContent={<b>Дом не куплен</b>} onClick={props.go} data-to="house">Дом</Cell>;
    }
    else {
        return <Cell before={<Icon24Home className="CellIconCustom" />} asideContent={props.user && <b>{`${props.user.house_name}`}</b>} onClick={props.go} data-to="myHouse">Дом</Cell>;
    }  
}

const Home = ({ id, add1, userAcc, clicks, reputation, speed, aspeed, fetchedUser, go, priv, name, dark, light }) => (
	<Panel id="home">
		<Div>
			<div className="row">
				<div className="col-md-12">
					<div className="text-center text-muted">
						<center><small>ВАШ СЧЕТ</small></center>
						<center><h1>{parseFloat(clicks).toFixed(3)} PC</h1></center>
						<center><p>Авто-майнинг: + {parseFloat(aspeed).toFixed(3)}</p></center>
					</div>
                <div className="container">

                    <Div align="center">
                        <img src={require('../img/click.png')} onClick={add1} width={250} height={250} />
                    </Div>
                    
				</div>
                <div className="text-center buttons">
						<div className="d-flex justify-content-around">
							<div className="justify-content-around">
								<button className="btn btn-rating">
									<Icon24Game className="buttons_custom" onClick={go} data-to="top" />
								</button>
								<p className="text-buttons text-muted text-monospace">Топ</p>
							</div>
							<div className="justify-content-around">
								<button className="btn btn-exchange">
									<Icon24Send className="buttons_custom" onClick={go} data-to="dop" />
								</button>
								<p className="text-buttons text-muted text-monospace">Перевод</p>
							</div>
							<div className="justify-content-around">
								<button className="btn btn-shop" onClick={go} data-to="persik">
									<Icon24Gift className="buttons_custom" />
								</button>
								<p className="text-buttons text-muted text-monospace">Магазин</p>
							</div>
						</div>
					</div>
				</div>
			</div>
		</Div>
	</Panel>
);



Home.propTypes = {
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
export default Home;
