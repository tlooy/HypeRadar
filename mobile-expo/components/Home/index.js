// https://docs.expo.dev/push-notifications/overview/

import Constants from 'expo-constants';
import * as Notifications from 'expo-notifications';
import React, { useState, useEffect, useRef } from 'react';
import { Component, Text, View, Button, Platform, FlatList } from 'react-native';
import styles from './style';

Notifications.setNotificationHandler({
	handleNotification: async () => ({
		shouldShowAlert: true,
		shouldPlaySound: true,
		shouldSetBadge: false,
	}),
});

// TODO: Get these two variables working with useState rather than global variables
let userID;
let eventArray = [];

//let registeredFlag = false;

export default function App({ navigation }) {
	const [expoPushToken, setExpoPushToken] = useState('');
	const [notification, setNotification] = useState(false);
	const [registeredFlag, setRegisteredFlag] = useState(false);

	const [events, setEvents] = useState( { data : [] });

	const notificationListener = useRef();
	const responseListener = useRef();

// TODO: why doesn't this work?  I get an occasional promise rejection saying "Can't find variable: userID 
//	const [userID, setUserID] = useState(navigation.getParam('UserID'));

	userID = navigation.getParam('UserID');
	useEffect(() => {
		getPushTokenFromExpo()
		.then(token => checkForExpoPushTokenInDatabase(token))
		.then(token => setExpoPushToken(token));

/* 	This doesn't work and I don't know why.  I put the contents of the getuserSubscribedEvents
	call inline below to get it to work
		Response = getUserSubscribedEvents();
		.then(Response => { 
			console.log("2 - Response: ", Response);
			setEvents( { data: Response } )
			eventArray = Response;
			console.log("3 - eventArray: ", eventArray);
		} );
*/



		var APIURL = "http://10.0.0.40/HypeRadar/mobile-expo/backend/fetchEvents.php";

		var headers = {
			'Accept' : 'application/json',
			'Content-Type' : 'application/json'
		};

		var Data = {
			UserID: userID
		};

		fetch(APIURL,{
			method: 'POST',
			headers: headers,
			body: JSON.stringify(Data)
		})
		.then(Response => Response.json())
		.then(Response => {
			setEvents( { data: Response } ); 
			console.log("Response: ", Response);
			console.log("events.data: ", events.data.Events);
		})
		.catch((error)=>{
			console.error("ERROR FOUND" + error);
		})







		// This listener is fired whenever a notification is received while the app is foregrounded
		notificationListener.current = Notifications.addNotificationReceivedListener(notification => {
			setNotification(notification);
		});

		// This listener is fired whenever a user taps on or interacts with a notification 
		// (works when app is foregrounded, backgrounded, or killed)
		responseListener.current = Notifications.addNotificationResponseReceivedListener(response => {
			console.log(response);
		});

		return () => {
			Notifications.removeNotificationSubscription(notificationListener.current);
			Notifications.removeNotificationSubscription(responseListener.current);
		};
	}, []);
	
	return (
		<View
			style={{
				flex: 1,
				alignText: 'left',
				justifyContent: 'space-around',
			}}>
			<Text>User ID: { userID }{"\n"}</Text>

			<Text>{ expoPushToken }{"\n"}</Text>

			<Text>Your Subscribed Events</Text>
			<Text>----------------------</Text>
			<FlatList 
				data={ events.data.Events }
				keyExtractor={(x, i) => i}
				renderItem={({item}) => 
					<Text>
						{item.name} 
					</Text>}
			/>

			<Button
				title="Go to your Topics"
				onPress= {() =>  {
					navigation.navigate("TopicsScreen", {UserID: userID});
				}}
	      		/>
		</View>
	);
}

async function getPushTokenFromExpo() {
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

  return token;
}

async function checkForExpoPushTokenInDatabase(expoPushToken) {
		// TODO change this to localhost or something else from config...
		var APIURL = "http://10.0.0.40/HypeRadar/mobile-expo/backend/fetchDID.php";

		var headers = {
			'Accept' : 'application/json',
			'Content-Type' : 'application/json'
		};

		var Data = {
			Token: expoPushToken,
			UserID: userID
		};

		fetch(APIURL,{
			method: 'POST',
			headers: headers,
			body: JSON.stringify(Data)
		})
		.then((Response)=>Response.json())
		.then((Response)=>{
			if (Response[0].Message == "Not Registered") {
				saveExpoPushToken(expoPushToken, userID);
			}
		})
		.catch((error)=>{
			console.error("ERROR FOUND" + error);
		})

	return expoPushToken;
}

async function saveExpoPushToken(expoPushToken, userID) {
		// TODO change this to localhost or something else from config...
		var APIURL = "http://10.0.0.40/HypeRadar/mobile-expo/backend/saveDID.php";

		var headers = {
			'Accept' : 'application/json',
			'Content-Type' : 'application/json'
		};

		var Data = {
			Token: expoPushToken,
			UserID: userID
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
	return expoPushToken;
}  

// I couldn't get this function to play nicely with the .then calls earlier in the code
function getUserSubscribedEvents() {
//async function getUserSubscribedEvents() {
	// TODO change this to localhost or something else from config...
	var APIURL = "http://10.0.0.40/HypeRadar/mobile-expo/backend/fetchEvents.php";

	var headers = {
		'Accept' : 'application/json',
		'Content-Type' : 'application/json'
	};

	var Data = {
		UserID: userID
	};

	fetch(APIURL,{
		method: 'POST',
		headers: headers,
		body: JSON.stringify(Data)
	})
	.then(Response => Response.json())
	.then(Response => {

		console.log("1 - Response: ", Response);
		return Response;	
	})
	.catch((error)=>{
		console.error("ERROR FOUND" + error);
	})

//	return Response;
}  