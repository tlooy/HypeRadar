// https://docs.expo.dev/push-notifications/overview/

import Constants from 'expo-constants';
import * as Notifications from 'expo-notifications';
import React, { Component, useState, useEffect } from 'react';
import { Text, View, Button, Platform, FlatList } from 'react-native';
import styles from './style';

export default class Home extends Component {
	constructor(props) {
		super(props);

		this.state = {
			userID: "",
			expoPushToken: '',
			notification: false,
			registeredFlag: false,
			events: [] 
		}

		Notifications.setNotificationHandler({
			handleNotification: async () => ({
				shouldShowAlert: true,
				shouldPlaySound: true,
				shouldSetBadge: false,
			}),
		})
	}

	componentDidMount() {
		this.state.userID = this.props.navigation.getParam('UserID');
 		this.fetchEvents();

		this.getPushTokenFromExpo()
		.then(token => this.checkForExpoPushTokenInDatabase(token))
		.catch((error)=> {
			console.error("ERROR FOUND" + error);
		})
 	}
	
	render() {
		return (
			<View
				style={{
					flex: 1,
					alignText: 'left',
					justifyContent: 'space-around',
				}}>
				<Text>UserID: { this.state.userID }{"\n"}
					{ global.environment }{"\n"}
					{ this.state.expoPushToken }{"\n"}</Text>

				<Text>Your Subscribed Events</Text>

				<Text>----------------------</Text>
				<FlatList
					data={ this.state.events.Events }
					keyExtractor={(x, i) => i}
					renderItem={({item}) =>
						<Text>
							{item.name}
						</Text>}
				/>

				<Button
					title="Go to your Topics"
					onPress= {() =>  {
						this.props.navigation.navigate("TopicsScreen", {UserID: this.state.userID });
					}}
		      		/>
			</View>
		);
	}	

	fetchEvents = async() => {
		var APIURL = global.environment + "fetchEvents.php";

		var headers = {
			'Accept' : 'application/json',
			'Content-Type' : 'application/json'
		};

		var Data = {
			UserID: this.state.userID
		};

		fetch(APIURL,{
			method: 'POST',
			headers: headers,
			body: JSON.stringify(Data)
		})
		.then(Response => Response.json())
		.then(Response => {
			this.setState({events: Response }); 
		})
		.catch((error)=>{
			console.error("ERROR FOUND" + error);
		})
	};

	getPushTokenFromExpo = async() => {
		let token;
		if (Constants.isDevice) {
			const { status: existingStatus } = await Notifications.getPermissionsAsync();
			let finalStatus = existingStatus;
			if (existingStatus !== 'granted') {
				const { status } = await Notifications.requestPermissionsAsync();
				finalStatus = status;
			}
			if (finalStatus !== 'granted') {
				alert('Failed to get push token for push notification!');
				return;
			}
			token = (await Notifications.getExpoPushTokenAsync()).data;
		} else {
			alert('Must use physical device for Push Notifications');
		}

		if (Platform.OS === 'android') {
			Notifications.setNotificationChannelAsync('default', {
				name: 'default',
				importance: Notifications.AndroidImportance.MAX,
				vibrationPattern: [0, 250, 250, 250],
				lightColor: '#FF231F7C',
			});
		}
		this.state.expoPushToken = token;
	  	return token;
	}

	checkForExpoPushTokenInDatabase = async() => {
		var APIURL = global.environment + "fetchDID.php";

		var headers = {
			'Accept' : 'application/json',
			'Content-Type' : 'application/json'
		};

		var Data = {
			Token: this.state.expoPushToken,
			UserID: this.state.userID
		};

		fetch(APIURL,{
			method: 'POST',
			headers: headers,
			body: JSON.stringify(Data)
		})
		.then((Response)=>Response.json())
		.then((Response)=>{
			if (Response[0].Message == "Not Registered") {
				this.saveExpoPushToken();
			}
		})
		.catch((error)=>{
			console.error("ERROR FOUND" + error);
		})

		return this.state.expoPushToken;
	}

	saveExpoPushToken = async() => {
		// TODO change this to localhost or something else from config...
		var APIURL = global.environment + "saveDID.php";

		var headers = {
			'Accept' : 'application/json',
			'Content-Type' : 'application/json'
		};

		var Data = {
			Token: this.state.expoPushToken,
			UserID: this.state.userID
		};

		fetch(APIURL,{
			method: 'POST',
			headers: headers,
			body: JSON.stringify(Data)
		})
		.then((Response)=>Response.json())
		.then((Response)=>{
			if (Response[0].Message != "Success") {
				alert(Response[0].Message);
			} else {
				alert("Successfully registered this device to receive real-time notifications.");
			} 		
		})
		.catch((error)=>{
			console.error("ERROR FOUND" + error);
		})
		return this.state.expoPushToken;
	}
}