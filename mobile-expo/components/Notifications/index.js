// https://www.youtube.com/watch?v=IuYo009yc8w


import React, { Component, useEffect } from 'react';
import { View, Text, FlatList} from 'react-native';
import styles from './style';

export default class Notifications extends Component {

	constructor(props) {
		super(props);
	}
	
	userID = "";
	state = { data : [] };
/* TODO: got to get this to work.  Currently returns an 'Unexpected token' error on second '('.

	useEffect(() => {
		this.fetchNotifications();		
	}, []);
*/
	componentDidMount() {
		this.fetchNotifications();	
	}
	fetchNotifications = async() => {
	this.userID = this.props.navigation.getParam('UserID');
		var APIURL = global.environment + "fetchNotifications.php";

		var headers = {
			'Accept' : 'application/json',
			'Content-Type' : 'application/json'
		};

		var Data ={
			'UserID': this.userID
		};

		const response = await fetch(APIURL,{
				method: 'POST',
				headers: headers,
				body: JSON.stringify(Data)
			});

		const json	= await response.json();
		this.setState({data: json.Notifications});
	} 

	render() {
		return (
			<View style={styles.view}>
				<Text>UserID: { this.userID }</Text>
				<Text>Events | Notifications</Text>
				<FlatList 
					data={this.state.data}
					keyExtractor={(x, i) => i}
					renderItem={({item}) => 
						<Text>
							{item.name} | {item.topic}
						</Text>}
				/>
			</View>
		);		
	
	}

}

