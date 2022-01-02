// https://docs.expo.dev/push-notifications/overview/

import Constants from 'expo-constants';
import * as Notifications from 'expo-notifications';
import React, { useState, useEffect, useRef } from 'react';
import { Text, View, Button, Platform } from 'react-native';
import styles from './style';

Notifications.setNotificationHandler({
	handleNotification: async () => ({
		shouldShowAlert: true,
		shouldPlaySound: true,
		shouldSetBadge: false,
	}),
});

// Make userID global so it can be available when we persist the notification device in a method called in a promise that already sends the token
let userID;

export default function App({ navigation }) {
	const [expoPushToken, setExpoPushToken] = useState('');
	const [notification, setNotification] = useState(false);
	const notificationListener = useRef();
	const responseListener = useRef();

	userID = navigation.getParam('UserID');
	useEffect(() => {
		registerForPushNotificationsAsync()
		.then(token => setExpoPushToken(token))
		.then(saveDeviceTokenToDatabase(expoPushToken));
		
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
				alignItems: 'center',
				justifyContent: 'space-around',
			}}>
			<Text>User ID: { userID }</Text>

			<Text>Expo push token: {expoPushToken}</Text>
			<View style={{ alignItems: 'center', justifyContent: 'center' }}>
				<Text>Title: {notification && notification.request.content.title} </Text>
				<Text>Body: {notification && notification.request.content.body}</Text>
				<Text>Data: {notification && JSON.stringify(notification.request.content.data)}</Text>
			</View>
			<Button
				title="Press to Send Notification"
				onPress={async () => {
					await sendPushNotification(expoPushToken);
				}}
	      		/>
		</View>
	);
}

// Can use this function below, OR use Expo's Push Notification Tool-> https://expo.dev/notifications
async function sendPushNotification(expoPushToken) {
  const message = {
    to: expoPushToken,
    sound: 'default',
    title: 'Original Title',
    body: 'And here is the body!',
    data: { someData: 'goes here' },
  };

  await fetch('https://exp.host/--/api/v2/push/send', {
    method: 'POST',
    headers: {
      Accept: 'application/json',
      'Accept-encoding': 'gzip, deflate',
      'Content-Type': 'application/json',
    },
    body: JSON.stringify(message),
  });
}

async function registerForPushNotificationsAsync() {
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

async function saveDeviceTokenToDatabase(expoPushToken) {
//	try {
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
			alert(Response[0].Message)
			if (Response[0].Message == "Success") {
				  console.log("DID successfully saved to User Account");
			}
			console.log("Data Object after the fetch = ", Data);
		})
		.catch((error)=>{
			console.error("ERROR FOUND" + error);
		})
/*
	} catch (error) {
		console.log(error);
	}
*/
}


