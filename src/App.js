/* eslint-disable */
import React from 'react';
import connect from '@vkontakte/vkui-connect';
import { View, Alert} from '@vkontakte/vkui';
import '@vkontakte/vkui/dist/vkui.css';

import Home from './panels/Home';
import Persik from './panels/Persik';
import House from './panels/House';
import Pet from './panels/Pet';
import MyPet from './panels/MyPet';
import MyHouse from './panels/MyHouse';
import Dop from './panels/Dop';
import Top from './panels/Top';
import Reit from './panels/Reit';
import Sett from './panels/Sett';
import ChangeNickname from './panels/ChangeNickname';
import Transfer from './panels/Transfer';
import Katalog from './panels/Katalog';
import Imus from './panels/Imus';
import Upgrade from './panels/Upgrade';


class App extends React.Component {
	constructor(props) {
		super(props);

		this.state = {
			activePanel: 'home',
            fetchedUser: null,
            userAcc: null,
			activeTab6: 'all',
            clicks: 0,
            reputation: 0,
            speed: 0,
            aspeed: 0,
            priv: null,
            name: false,
            popout: null,
            calcRep: 0,
            nick: null,
            toid: 0,
            history: 0,
            history1: null,
            history2: null,
            history3: null,
            history4: null,
            history5: null,
            history6: null,
            history7: null,
            history8: null,
            history9: null,
            history10: null,
            h_id1: 0,
            h_id2: 0,
            h_id3: 0,
            h_id4: 0,
            h_id5: 0,
            h_id6: 0,
            h_id7: 0,
            h_id8: 0,
            h_id9: 0,
            h_id10: 0,
            h_s1: 0,
            h_s2: 0,
            h_s3: 0,
            h_s4: 0,
            h_s5: 0,
            h_s6: 0,
            h_s7: 0,
            h_s8: 0,
            h_s9: 0,
            h_s10: 0,
            viv: 0,
            v1: null,
            v2: null,
            v3: null,
            v4: null,
            v5: null,
            v6: null,
            v7: null,
            v8: null,
            v9: null,
            v10: null,
            v_id1: 0,
            v_id2: 0,
            v_id3: 0,
            v_id4: 0,
            v_id5: 0,
            v_id6: 0,
            v_id7: 0,
            v_id8: 0,
            v_id9: 0,
            v_id10: 0,
            v_s1: 0,
            v_s2: 0,
            v_s3: 0,
            v_s4: 0,
            v_s5: 0,
            v_s6: 0,
            v_s7: 0,
            v_s8: 0,
            v_s9: 0,
            v_s10: 0,
            transferCoins: 0,
            usersTopClicks: null,
            usersTopReputation: null,
            activeTabUpgrades: 'click',
            activeTabTop: 'click',
            upgradesOne: null,
            upgradesTwo: null,
            sp: 0
		};
        this.openDefault = this.openDefault.bind(this);
        this.closePopout = this.closePopout.bind(this);
        this.handleReputationChange = this.handleReputationChange.bind(this);
        this.handleReputationSubmit = this.handleReputationSubmit.bind(this);
        this.handleNickChange = this.handleNickChange.bind(this);
        this.handleNickSubmit = this.handleNickSubmit.bind(this);
        this.handleTransferChange = this.handleTransferChange.bind(this);
        this.handleTransferSubmit = this.handleTransferSubmit.bind(this);
        this.buyPet = this.buyPet.bind(this);
        this.sellPet = this.sellPet.bind(this);
        this.pohodPet = this.pohodPet.bind(this);
        this.buyHouse = this.buyHouse.bind(this);
        this.sellHouse = this.sellHouse.bind(this);
        this.pohodHouse = this.pohodHouse.bind(this);
        this.setActiveTabUpgrades = this.setActiveTabUpgrades.bind(this);
        this.setActiveTabTop = this.setActiveTabTop.bind(this);
        this.buyUpgrade = this.buyUpgrade.bind(this);
        this.getUpgradesOne = this.getUpgradesOne.bind(this);
        this.getUpgradesTwo = this.getUpgradesTwo.bind(this);
        this.autoMine = this.autoMine.bind(this);
    }
    

    openDefault(title, msg, actions) {
        this.setState({
            popout:
                <Alert
                    actions={[actions]}
                    onClose={this.closePopout}
                >   
                    <h2>{title}</h2>
                    <p>{msg}</p>
                </Alert>
        });
    }

    closePopout() {
        this.setState({ popout: null });
    }

	componentDidMount() {
		connect.subscribe((e) => {
			switch (e.detail.type) {
				case 'VKWebAppGetUserInfoResult':
                    this.setState({ fetchedUser: e.detail.data });
                    //console.log(e.detail.data)
                    this.createUser(e.detail.data);
                    this.getUsersTopClicks();
                    this.getUsersTopReputation();
                    this.getUpgradesOne()
                    this.getUpgradesTwo()
					break;
					case 'VKWebAppShowWallPostBoxResult':

  					break;
				default:
					console.log(e.detail.type);
			}
		});
		connect.send('VKWebAppGetUserInfo', {});
        setInterval(() => {
            this.autoMine();
            console.log(1);
        }, 1000)
	}

	go = (e) => {
		this.setState({ activePanel: e.currentTarget.dataset.to })
        if(e.currentTarget.dataset.to === "top") {
            this.getUsersTopClicks();
            this.getUsersTopReputation();
        }
	}

    createUser = (data) => {
        fetch("/api.php", {
            method: 'post',
            headers: {
                "Content-type": "application/x-www-form-urlencoded; charset=UTF-8"
            },
            body: `method=user.create`
        })
            .then((response) => response.json())
            .then((response) => {
                this.setState({ history: response.history })
                this.setState({ history1: response.history1 })
                this.setState({ history2: response.history2 })
                this.setState({ history3: response.history3 })
                this.setState({ history4: response.history4 })
                this.setState({ history5: response.history5 })
                this.setState({ history6: response.history6 })
                this.setState({ history7: response.history7 })
                this.setState({ history8: response.history8 })
                this.setState({ history9: response.history9 })
                this.setState({ history10: response.history10 })
                this.setState({ h_id1: response.h_id1 })
                this.setState({ h_id2: response.h_id2 })
                this.setState({ h_id3: response.h_id3 })
                this.setState({ h_id4: response.h_id4 })
                this.setState({ h_id5: response.h_id5 })
                this.setState({ h_id7: response.h_id7 })
                this.setState({ h_id8: response.h_id8 })
                this.setState({ h_id9: response.h_id9 })
                this.setState({ h_id10: response.h_id10 })
                this.setState({ h_id6: response.h_id6 })
                this.setState({ h_s1: response.h_s1 })
                this.setState({ h_s2: response.h_s2 })
                this.setState({ h_s3: response.h_s3 })
                this.setState({ h_s4: response.h_s4 })
                this.setState({ h_s5: response.h_s5 })
                this.setState({ h_s6: response.h_s6 })
                this.setState({ h_s7: response.h_s7 })
                this.setState({ h_s8: response.h_s8 })
                this.setState({ h_s9: response.h_s9 })
                this.setState({ h_s10: response.h_s10 })
                this.setState({ viv: response.viv })
                this.setState({ v1: response.v1 })
                this.setState({ v2: response.v2 })
                this.setState({ v3: response.v3 })
                this.setState({ v4: response.v4 })
                this.setState({ v5: response.v5 })
                this.setState({ v6: response.v6 })
                this.setState({ v7: response.v7 })
                this.setState({ v8: response.v8 })
                this.setState({ v9: response.v9 })
                this.setState({ v10: response.v10 })
                this.setState({ v_id1: response.v_id1 })
                this.setState({ v_id2: response.v_id2 })
                this.setState({ v_id3: response.v_id3 })
                this.setState({ v_id4: response.v_id4 })
                this.setState({ v_id5: response.v_id5 })
                this.setState({ v_id7: response.v_id7 })
                this.setState({ v_id8: response.v_id8 })
                this.setState({ v_id9: response.v_id9 })
                this.setState({ v_id10: response.v_id10 })
                this.setState({ v_id6: response.v_id6 })
                this.setState({ v_s1: response.v_s1 })
                this.setState({ v_s2: response.v_s2 })
                this.setState({ v_s3: response.v_s3 })
                this.setState({ v_s4: response.v_s4 })
                this.setState({ v_s5: response.v_s5 })
                this.setState({ v_s6: response.v_s6 })
                this.setState({ v_s7: response.v_s7 })
                this.setState({ v_s8: response.v_s8 })
                this.setState({ v_s9: response.v_s9 })
                this.setState({ v_s10: response.v_s10 })
               // console.log(response)
                this.setState({ userAcc: response })
                this.setState({ clicks: response.clicks, reputation: response.reputation, speed: response.speed, aspeed: response.aspeed })
                //console.log(this.state.userAcc)
            })
            .catch((error) => {
                console.log(error)
            })
    }

    updateUserClicks = (id) => {
        fetch("/api.php", {
            method: 'post',
            headers: {
                "Content-type": "application/x-www-form-urlencoded; charset=UTF-8"
            },
            body: `method=user.update.click&user_vk=${id}`
        })
            .then((response) => response.json())
            .then((response) => {
                console.log('user', response)
                this.setState({ userAcc: response })
                this.setState({ clicks: response.clicks, reputation: response.reputation, speed: response.speed, aspeed: response.aspeed })
                this.setState({ history: response.history })
                this.setState({ history1: response.history1 })
                this.setState({ history2: response.history2 })
                this.setState({ history3: response.history3 })
                this.setState({ history4: response.history4 })
                this.setState({ history5: response.history5 })
                this.setState({ history6: response.history6 })
                this.setState({ history7: response.history7 })
                this.setState({ history8: response.history8 })
                this.setState({ history9: response.history9 })
                this.setState({ history10: response.history10 })
                this.setState({ h_id1: response.h_id1 })
                this.setState({ h_id2: response.h_id2 })
                this.setState({ h_id3: response.h_id3 })
                this.setState({ h_id4: response.h_id4 })
                this.setState({ h_id5: response.h_id5 })
                this.setState({ h_id7: response.h_id7 })
                this.setState({ h_id8: response.h_id8 })
                this.setState({ h_id9: response.h_id9 })
                this.setState({ h_id10: response.h_id10 })
                this.setState({ h_id6: response.h_id6 })
                this.setState({ h_s1: response.h_s1 })
                this.setState({ h_s2: response.h_s2 })
                this.setState({ h_s3: response.h_s3 })
                this.setState({ h_s4: response.h_s4 })
                this.setState({ h_s5: response.h_s5 })
                this.setState({ h_s6: response.h_s6 })
                this.setState({ h_s7: response.h_s7 })
                this.setState({ h_s8: response.h_s8 })
                this.setState({ h_s9: response.h_s9 })
                this.setState({ h_s10: response.h_s10 })
                this.setState({ viv: response.viv })
                this.setState({ v1: response.v1 })
                this.setState({ v2: response.v2 })
                this.setState({ v3: response.v3 })
                this.setState({ v4: response.v4 })
                this.setState({ v5: response.v5 })
                this.setState({ v6: response.v6 })
                this.setState({ v7: response.v7 })
                this.setState({ v8: response.v8 })
                this.setState({ v9: response.v9 })
                this.setState({ v10: response.v10 })
                this.setState({ v_id1: response.v_id1 })
                this.setState({ v_id2: response.v_id2 })
                this.setState({ v_id3: response.v_id3 })
                this.setState({ v_id4: response.v_id4 })
                this.setState({ v_id5: response.v_id5 })
                this.setState({ v_id7: response.v_id7 })
                this.setState({ v_id8: response.v_id8 })
                this.setState({ v_id9: response.v_id9 })
                this.setState({ v_id10: response.v_id10 })
                this.setState({ v_id6: response.v_id6 })
                this.setState({ v_s1: response.v_s1 })
                this.setState({ v_s2: response.v_s2 })
                this.setState({ v_s3: response.v_s3 })
                this.setState({ v_s4: response.v_s4 })
                this.setState({ v_s5: response.v_s5 })
                this.setState({ v_s6: response.v_s6 })
                this.setState({ v_s7: response.v_s7 })
                this.setState({ v_s8: response.v_s8 })
                this.setState({ v_s9: response.v_s9 })
                this.setState({ v_s10: response.v_s10 })
            })
            .catch((error) => {
                console.error(error)
            })
    }

    updateUserReputation = (id, reputation) => {
        fetch("/api.php", {
            method: 'post',
            headers: {
                "Content-type": "application/x-www-form-urlencoded; charset=UTF-8"
            },
            body: `method=user.update.reputation&user_vk=${id}&reputation_new=${reputation}`
        })
            .then((response) => response.json())
            .then((response) => {
               //console.log(response)
                switch (response.status) {
                    case 'success':
                        this.openDefault("Успех", response.msg, {
                            title: 'ОК',
                            autoclose: true,
                        })
                        break;
                    case 'error':
                        this.openDefault("Ошибка", response.msg, {
                            title: 'ОК',
                            autoclose: true,
                            style: "destructive"
                        })
                        break;
                    default: break;
                }
                this.setState({ userAcc: response })
                this.setState({ clicks: response.clicks, reputation: response.reputation, speed: response.speed, aspeed: response.aspeed })
                this.setState({ history: response.history })
                this.setState({ history1: response.history1 })
                this.setState({ history2: response.history2 })
                this.setState({ history3: response.history3 })
                this.setState({ history4: response.history4 })
                this.setState({ history5: response.history5 })
                this.setState({ history6: response.history6 })
                this.setState({ history7: response.history7 })
                this.setState({ history8: response.history8 })
                this.setState({ history9: response.history9 })
                this.setState({ history10: response.history10 })
                this.setState({ h_id1: response.h_id1 })
                this.setState({ h_id2: response.h_id2 })
                this.setState({ h_id3: response.h_id3 })
                this.setState({ h_id4: response.h_id4 })
                this.setState({ h_id5: response.h_id5 })
                this.setState({ h_id7: response.h_id7 })
                this.setState({ h_id8: response.h_id8 })
                this.setState({ h_id9: response.h_id9 })
                this.setState({ h_id10: response.h_id10 })
                this.setState({ h_id6: response.h_id6 })
                this.setState({ h_s1: response.h_s1 })
                this.setState({ h_s2: response.h_s2 })
                this.setState({ h_s3: response.h_s3 })
                this.setState({ h_s4: response.h_s4 })
                this.setState({ h_s5: response.h_s5 })
                this.setState({ h_s6: response.h_s6 })
                this.setState({ h_s7: response.h_s7 })
                this.setState({ h_s8: response.h_s8 })
                this.setState({ h_s9: response.h_s9 })
                this.setState({ h_s10: response.h_s10 })
                this.setState({ viv: response.viv })
                this.setState({ v1: response.v1 })
                this.setState({ v2: response.v2 })
                this.setState({ v3: response.v3 })
                this.setState({ v4: response.v4 })
                this.setState({ v5: response.v5 })
                this.setState({ v6: response.v6 })
                this.setState({ v7: response.v7 })
                this.setState({ v8: response.v8 })
                this.setState({ v9: response.v9 })
                this.setState({ v10: response.v10 })
                this.setState({ v_id1: response.v_id1 })
                this.setState({ v_id2: response.v_id2 })
                this.setState({ v_id3: response.v_id3 })
                this.setState({ v_id4: response.v_id4 })
                this.setState({ v_id5: response.v_id5 })
                this.setState({ v_id7: response.v_id7 })
                this.setState({ v_id8: response.v_id8 })
                this.setState({ v_id9: response.v_id9 })
                this.setState({ v_id10: response.v_id10 })
                this.setState({ v_id6: response.v_id6 })
                this.setState({ v_s1: response.v_s1 })
                this.setState({ v_s2: response.v_s2 })
                this.setState({ v_s3: response.v_s3 })
                this.setState({ v_s4: response.v_s4 })
                this.setState({ v_s5: response.v_s5 })
                this.setState({ v_s6: response.v_s6 })
                this.setState({ v_s7: response.v_s7 })
                this.setState({ v_s8: response.v_s8 })
                this.setState({ v_s9: response.v_s9 })
                this.setState({ v_s10: response.v_s10 })
            })
            .catch((error) => {
                console.error(error)
            })
    }

    updateUserNickname = (id, nickname) => {
        fetch("/api.php", {
            method: 'post',
            headers: {
                "Content-type": "application/x-www-form-urlencoded; charset=UTF-8"
            },
            body: `method=user.update.nickname&user_vk=${id}&nickname=${nickname}`
        })
            .then((response) => response.json())
            .then((response) => {
                //console.log(response)
                switch (response.status) {
                    case 'success':
                        this.openDefault("Успех", response.msg, {
                            title: 'ОК',
                            autoclose: true,
                        })
                        break;
                    case 'error':
                        this.openDefault("Ошибка", response.msg, {
                            title: 'ОК',
                            autoclose: true,
                            style: "destructive"
                        })
                        break;
                    default: break;
                }
                this.setState({ userAcc: response })
                this.setState({ clicks: response.clicks, reputation: response.reputation, speed: response.speed, aspeed: response.aspeed })
            })
            .catch((error) => {
                console.error(error)
            })
    }

    sendTransfer = (id, toid, count) => {
        fetch("/api.php", {
            method: 'post',
            headers: {
                "Content-type": "application/x-www-form-urlencoded; charset=UTF-8"
            },
            body: `method=user.send.transfer&user_vk=${id}&toid=${toid}&count=${count}`
        })
            .then((response) => response.json())
            .then((response) => {
                //console.log(response)
                switch (response.status) {
                    case 'success':
                        this.openDefault("Успех", response.msg, {
                            title: 'ОК',
                            autoclose: true,
                        })
                        break;
                    case 'error':
                        this.openDefault("Ошибка", response.msg, {
                            title: 'ОК',
                            autoclose: true,
                            style: "destructive"
                        })
                        break;
                    default: break;
                }
                this.setState({ userAcc: response })
                this.setState({ clicks: response.clicks, reputation: response.reputation, speed: response.speed, aspeed: response.aspeed })
                this.setState({ history: response.history })
                this.setState({ history1: response.history1 })
                this.setState({ history2: response.history2 })
                this.setState({ history3: response.history3 })
                this.setState({ history4: response.history4 })
                this.setState({ history5: response.history5 })
                this.setState({ history6: response.history6 })
                this.setState({ history7: response.history7 })
                this.setState({ history8: response.history8 })
                this.setState({ history9: response.history9 })
                this.setState({ history10: response.history10 })
                this.setState({ h_id1: response.h_id1 })
                this.setState({ h_id2: response.h_id2 })
                this.setState({ h_id3: response.h_id3 })
                this.setState({ h_id4: response.h_id4 })
                this.setState({ h_id5: response.h_id5 })
                this.setState({ h_id7: response.h_id7 })
                this.setState({ h_id8: response.h_id8 })
                this.setState({ h_id9: response.h_id9 })
                this.setState({ h_id10: response.h_id10 })
                this.setState({ h_id6: response.h_id6 })
                this.setState({ h_s1: response.h_s1 })
                this.setState({ h_s2: response.h_s2 })
                this.setState({ h_s3: response.h_s3 })
                this.setState({ h_s4: response.h_s4 })
                this.setState({ h_s5: response.h_s5 })
                this.setState({ h_s6: response.h_s6 })
                this.setState({ h_s7: response.h_s7 })
                this.setState({ h_s8: response.h_s8 })
                this.setState({ h_s9: response.h_s9 })
                this.setState({ h_s10: response.h_s10 })
                this.setState({ viv: response.viv })
                this.setState({ v1: response.v1 })
                this.setState({ v2: response.v2 })
                this.setState({ v3: response.v3 })
                this.setState({ v4: response.v4 })
                this.setState({ v5: response.v5 })
                this.setState({ v6: response.v6 })
                this.setState({ v7: response.v7 })
                this.setState({ v8: response.v8 })
                this.setState({ v9: response.v9 })
                this.setState({ v10: response.v10 })
                this.setState({ v_id1: response.v_id1 })
                this.setState({ v_id2: response.v_id2 })
                this.setState({ v_id3: response.v_id3 })
                this.setState({ v_id4: response.v_id4 })
                this.setState({ v_id5: response.v_id5 })
                this.setState({ v_id7: response.v_id7 })
                this.setState({ v_id8: response.v_id8 })
                this.setState({ v_id9: response.v_id9 })
                this.setState({ v_id10: response.v_id10 })
                this.setState({ v_id6: response.v_id6 })
                this.setState({ v_s1: response.v_s1 })
                this.setState({ v_s2: response.v_s2 })
                this.setState({ v_s3: response.v_s3 })
                this.setState({ v_s4: response.v_s4 })
                this.setState({ v_s5: response.v_s5 })
                this.setState({ v_s6: response.v_s6 })
                this.setState({ v_s7: response.v_s7 })
                this.setState({ v_s8: response.v_s8 })
                this.setState({ v_s9: response.v_s9 })
                this.setState({ v_s10: response.v_s10 })
            })
            .catch((error) => {
                console.error(error)
            })
    }

    getUsersTopClicks = () => {
        fetch("/api.php", {
            method: 'post',
            headers: {
                "Content-type": "application/x-www-form-urlencoded; charset=UTF-8"
            },
            body: `method=get.users.top.clicks`
        })
            .then((response) => response.json())
            .then((response) => {
                this.setState({ usersTopClicks: response })
                console.log('clicks', response)
            })
            .catch((error) => {
                console.error(error)
            })
    }

    getUsersTopReputation = () => {
        fetch("/api.php", {
            method: 'post',
            headers: {
                "Content-type": "application/x-www-form-urlencoded; charset=UTF-8"
            },
            body: `method=get.users.top.reputation`
        })
            .then((response) => response.json())
            .then((response) => {
                this.setState({ usersTopReputation: response });
                console.log('reputation', response)
            })
            .catch((error) => {
                console.error(error)
            })
    }

    handleReputationChange(event) {
        const price = event.target.value;
        this.setState({ calcRep: price * 50000 });
        this.setState({ sp: price });
    }

    handleReputationSubmit(event) {
        if (this.state.clicks < 50000) {
            this.openDefault("Ошибка!!", "Недостаточно денег для покупки рейтинга. Курс: 1 rate = 50000 coins", {
                title: 'ОК',
                autoclose: true,
                style: "destructive"
            })
        }
        else {
            this.updateUserReputation(this.state.fetchedUser.id, this.state.sp)
        }
        event.preventDefault();
    }

    handleNickChange(event) {
        console.log(event.target)
        this.setState({ nick: event.target.value})
    }

    handleNickSubmit(event) {
        if (this.state.nick === null || this.state.nick === "") {
            return this.openDefault("Ошибка", "Введите ник", {
                title: 'ОК',
                autoclose: true,
                style: "destructive"
            })
        }
        else this.updateUserNickname(this.state.fetchedUser.id, this.state.nick)
        event.preventDefault();
    }

    handleTransferChange(event) {
        console.log(event.target)
        const target = event.target;
        const name = target.name;
        const value = target.value;
        this.setState({ [name]: value })
    }

    handleTransferSubmit(event) {
        if (this.state.toid === 0 && this.state.transferCoins === 0) return false
        else this.sendTransfer(this.state.fetchedUser.id, this.state.toId, this.state.transferCoins)
        event.preventDefault();
    }

    buyPet(pet_id) {
        fetch("/api.php", {
            method: 'post',
            headers: {
                "Content-type": "application/x-www-form-urlencoded; charset=UTF-8"
            },
            body: `method=user.buy.pet&user_vk=${this.state.fetchedUser.id}&pet_id=${pet_id}`
        })
            .then((response) => response.json())
            .then((response) => {
                //console.log(response)
                switch(response.status) {
                    case 'success':
                        this.openDefault("Успех", response.msg, {
                            title: 'ОК',
                            autoclose: true,
                        })
                        break;
                    case 'error':
                        this.openDefault("Ошибка", response.msg, {
                            title: 'ОК',
                            autoclose: true,
                            style: "destructive"
                        })
                        break;
                    default: break;
                }
                this.setState({ userAcc: response })
                this.setState({ clicks: response.clicks, reputation: response.reputation, speed: response.speed, aspeed: response.aspeed })
            })
            .catch((error) => {
                console.error(error)
            })
    }

    sellPet(pet_id) {
        fetch("/api.php", {
            method: 'post',
            headers: {
                "Content-type": "application/x-www-form-urlencoded; charset=UTF-8"
            },
            body: `method=user.sell.pet&user_vk=${this.state.fetchedUser.id}&pet_id=${pet_id}`
        })
            .then((response) => response.json())
            .then((response) => {
                //console.log(response)
                switch (response.status) {
                    case 'success':
                        this.openDefault("Успех", response.msg, {
                            title: 'ОК',
                            autoclose: true,
                        })
                        break;
                    case 'error':
                        this.openDefault("Ошибка", response.msg, {
                            title: 'ОК',
                            autoclose: true,
                            style: "destructive"
                        })
                        break;
                    default: break;
                }
                this.setState({ userAcc: response })
                this.setState({ clicks: response.clicks, reputation: response.reputation, speed: response.speed, aspeed: response.aspeed })
                this.setState({ activePanel: "home" })
            })
            .catch((error) => {
                console.error(error)
            })
    }

    pohodPet() {
        fetch("/api.php", {
            method: 'post',
            headers: {
                "Content-type": "application/x-www-form-urlencoded; charset=UTF-8"
            },
            body: `method=user.pohod.pet&user_vk=${this.state.fetchedUser.id}`
        })
            .then((response) => response.json())
            .then((response) => {
                //console.log(response)
                switch (response.status) {
                    case 'success':
                        this.openDefault("Успех", response.msg, {
                            title: 'ОК',
                            autoclose: true,
                        })
                        break;
                    case 'error':
                        this.openDefault("Ошибка", response.msg, {
                            title: 'ОК',
                            autoclose: true,
                            style: "destructive"
                        })
                        break;
                    default: break;
                }
                this.setState({ userAcc: response })
                this.setState({ clicks: response.clicks, reputation: response.reputation, speed: response.speed, aspeed: response.aspeed })
            })
            .catch((error) => {
                console.error(error)
            })
    }


    buyHouse(house_id) {
        fetch("/api.php", {
            method: 'post',
            headers: {
                "Content-type": "application/x-www-form-urlencoded; charset=UTF-8"
            },
            body: `method=user.buy.house&user_vk=${this.state.fetchedUser.id}&house_id=${house_id}`
        })
            .then((response) => response.json())
            .then((response) => {
                //console.log(response)
                switch(response.status) {
                    case 'success':
                        this.openDefault("Успех", response.msg, {
                            title: 'ОК',
                            autoclose: true,
                        })
                        break;
                    case 'error':
                        this.openDefault("Ошибка", response.msg, {
                            title: 'ОК',
                            autoclose: true,
                            style: "destructive"
                        })
                        break;
                    default: break;
                }
                this.setState({ userAcc: response })
                this.setState({ clicks: response.clicks, reputation: response.reputation, speed: response.speed, aspeed: response.aspeed })
            })
            .catch((error) => {
                console.error(error)
            })
    }

    sellHouse(house_id) {
        fetch("/api.php", {
            method: 'post',
            headers: {
                "Content-type": "application/x-www-form-urlencoded; charset=UTF-8"
            },
            body: `method=user.sell.house&user_vk=${this.state.fetchedUser.id}&house_id=${house_id}`
        })
            .then((response) => response.json())
            .then((response) => {
                //console.log(response)
                switch (response.status) {
                    case 'success':
                        this.openDefault("Успех", response.msg, {
                            title: 'ОК',
                            autoclose: true,
                        })
                        break;
                    case 'error':
                        this.openDefault("Ошибка", response.msg, {
                            title: 'ОК',
                            autoclose: true,
                            style: "destructive"
                        })
                        break;
                    default: break;
                }
                this.setState({ userAcc: response })
                this.setState({ clicks: response.clicks, reputation: response.reputation, speed: response.speed, aspeed: response.aspeed })
                this.setState({ activePanel: "home" })
            })
            .catch((error) => {
                console.error(error)
            })
    }

    pohodHouse() {
        fetch("/api.php", {
            method: 'post',
            headers: {
                "Content-type": "application/x-www-form-urlencoded; charset=UTF-8"
            },
            body: `method=user.pohod.house&user_vk=${this.state.fetchedUser.id}`
        })
            .then((response) => response.json())
            .then((response) => {
                //console.log(response)
                switch (response.status) {
                    case 'success':
                        this.openDefault("Успех", response.msg, {
                            title: 'ОК',
                            autoclose: true,
                        })
                        break;
                    case 'error':
                        this.openDefault("Ошибка", response.msg, {
                            title: 'ОК',
                            autoclose: true,
                            style: "destructive"
                        })
                        break;
                    default: break;
                }
                this.setState({ userAcc: response })
                this.setState({ clicks: response.clicks, reputation: response.reputation, speed: response.speed, aspeed: response.aspeed })
            })
            .catch((error) => {
                console.error(error)
            })
    }

    buyUpgrade(type, name) {
        fetch("/api.php", {
            method: 'post',
            headers: {
                "Content-type": "application/x-www-form-urlencoded; charset=UTF-8"
            },
            body: `method=user.buy.upgrade&user_vk=${this.state.fetchedUser.id}&type=${type}&up_name=${name}`
        })
            .then((response) => response.json())
            .then((response) => {
                //console.log(response)
                switch(response.status) {
                    case 'error':
                        this.openDefault("Ошибка", response.msg, {
                            title: 'ОК',
                            autoclose: true,
                            style: "destructive"
                        })
                        break;
                    default: break;
                }
                this.setState({ userAcc: response })
                this.setState({ clicks: response.clicks, reputation: response.reputation, speed: response.speed, aspeed: response.aspeed })
                this.getUpgradesOne()
                this.getUpgradesTwo()
            })
            .catch((error) => {
                console.error(error)
            })
    }

    getUpgradesOne() {
        fetch("/api.php", {
            method: 'post',
            headers: {
                "Content-type": "application/x-www-form-urlencoded; charset=UTF-8"
            },
            body: `method=get.upgrades.one&user_vk=${this.state.fetchedUser.id}`
        })
            .then((response) => response.json())
            .then((response) => {
                console.log(response)                
                this.setState({ upgradesOne: response });
            })
            .catch((error) => {
                console.error(error)
            })
    }

    getUpgradesTwo() {
        fetch("/api.php", {
            method: 'post',
            headers: {
                "Content-type": "application/x-www-form-urlencoded; charset=UTF-8"
            },
            body: `method=get.upgrades.two&user_vk=${this.state.fetchedUser.id}`
        })
            .then((response) => response.json())
            .then((response) => {
                console.log(response)                
                this.setState({ upgradesTwo: response });
            })
            .catch((error) => {
                console.error(error)
            })
    }

    autoMine() {
        fetch("/api.php", {
            method: 'post',
            headers: {
                "Content-type": "application/x-www-form-urlencoded; charset=UTF-8"
            },
            body: `method=user.auto.mine&user_vk=${this.state.fetchedUser.id}`
        })
            .then((response) => response.json())
            .then((response) => {
                console.log(response)                
                this.setState({ userAcc: response })
                this.setState({ clicks: response.clicks, reputation: response.reputation, speed: response.speed, aspeed: response.aspeed })
            })
            .catch((error) => {
                console.error(error)
            })
    }

    setActiveTabUpgrades = (value) => {
       this.setState({ activeTabUpgrades: `${value}` });
    }

    setActiveTabTop = (value) => {
       this.setState({ activeTabTop: `${value}` });
    }


	add1 = () => {
        this.updateUserClicks(this.state.fetchedUser.id)
	}


    dark = () => { 
        document.body.setAttribute( 'scheme','client_dark' ); 
    }
    
    light = () => { 
        document.body.setAttribute( 'scheme','client_light' ); 
    }


	render() {
		return (
			<View popout={this.state.popout} activePanel={this.state.activePanel}>
                <Home id="home" add1={this.add1} userAcc={this.state.userAcc} dark={this.dark} light={this.light} clicks={this.state.clicks} speed={this.state.speed} aspeed={this.state.aspeed} reputation={this.state.reputation} name={this.name} fetchedUser={this.state.fetchedUser} go={this.go} />
                <Persik id="persik" go={this.go} name={this.name} fetchedUser={this.state.fetchedUser} userAcc={this.state.userAcc} />
                <House id="house" go={this.go} buy={this.buyHouse} />
                <Imus id="imus" go={this.go} />
                <MyHouse id="myHouse" go={this.go} userAcc={this.state.userAcc} sell={this.sellHouse} pohod={this.pohodHouse} />
                <MyPet id="myPet" go={this.go} userAcc={this.state.userAcc} sell={this.sellPet} pohod={this.pohodPet} />
                <Upgrade id="upgrades" upgradesOne={this.state.upgradesOne} upgradesTwo={this.state.upgradesTwo} go={this.go} activeTab={this.state.activeTabUpgrades} setTab={this.setActiveTabUpgrades} buyUpgrade={this.buyUpgrade} />
                <Pet id="pet" go={this.go} buy={this.buyPet} />
                <Dop id="dop" go={this.go} />
                <Top id="top" go={this.go} usersTopClicks={this.state.usersTopClicks} usersTopReputation={this.state.usersTopReputation} activeTab={this.state.activeTabTop} setTab={this.setActiveTabTop} />
                <Reit id="reit" clicks={this.state.clicks} reputation={this.state.reputation} go={this.go} handleReputationChange={this.handleReputationChange} handleReputationSubmit={this.handleReputationSubmit} calcRep={this.state.calcRep} />
                <Sett id="sett" go={this.go} dark={this.dark} light={this.light} />
                <ChangeNickname id="changeNickname" go={this.go} handleNickChange={this.handleNickChange} handleNickSubmit={this.handleNickSubmit} userAcc={this.state.userAcc} />
                <Transfer id="Transfer" go={this.go} handleTransferChange={this.handleTransferChange} clicks={this.state.clicks} fetchedUser={this.state.fetchedUser} h_id1={this.state.h_id1} h_id2={this.state.h_id2} h_id3={this.state.h_id3} h_id4={this.state.h_id4} h_id5={this.state.h_id5} h_id6={this.state.h_id6} h_id7={this.state.h_id7} h_id8={this.state.h_id8} h_id9={this.state.h_id9} h_id10={this.state.h_id10} h_s1={this.state.h_s1} h_s2={this.state.h_s2} h_s3={this.state.h_s3} h_s4={this.state.h_s4} h_s5={this.state.h_s5} h_s6={this.state.h_s6} h_s7={this.state.h_s7} h_s8={this.state.h_s8} h_s9={this.state.h_s9} h_s10={this.state.h_s10} history={this.state.history} history1={this.state.history1} history2={this.state.history2} history3={this.state.history3} history4={this.state.history4} history5={this.state.history5} history6={this.state.history6} history7={this.state.history7} history8={this.state.history8} history9={this.state.history9} history10={this.state.history10} v_id1={this.state.v_id1} v_id2={this.state.v_id2} v_id3={this.state.v_id3} v_id4={this.state.v_id4} v_id5={this.state.v_id5} v_id6={this.state.v_id6} v_id7={this.state.v_id7} v_id8={this.state.v_id8} v_id9={this.state.v_id9} v_id10={this.state.v_id10} v_s1={this.state.v_s1} v_s2={this.state.v_s2} v_s3={this.state.v_s3} v_s4={this.state.v_s4} v_s5={this.state.v_s5} v_s6={this.state.v_s6} v_s7={this.state.v_s7} v_s8={this.state.v_s8} v_s9={this.state.v_s9} v_s10={this.state.v_s10} viv={this.state.viv} v1={this.state.v1} v2={this.state.v2} v3={this.state.v3} v4={this.state.v4} v5={this.state.v5} v6={this.state.v6} v7={this.state.v7} v8={this.state.v8} v9={this.state.v9} v10={this.state.v10} handleTransferSubmit={this.handleTransferSubmit} userAcc={this.state.userAcc} />
                <Katalog id="katalog" go={this.go} />
			</View>
		);
	}
}

export default App;
