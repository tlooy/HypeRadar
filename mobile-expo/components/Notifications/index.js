// https://www.youtube.com/watch?v=IuYo009yc8w


import React, { useEffect } from 'react';
import { View, Text, FlatList} from 'react-native';
import styles from './style';

export default function App({ navigation }) {
	userID = navigation.getParam('UserID');

	useEffect(() => {
		fetchNotifications();		
	}, []);

	fetchNotifications = async() => {
		var APIURL = "http://10.0.0.40/HypeRadar/mobile-expo/backend/fetchNotifications.php";

		var headers = {
			'Accept' : 'application/json',
			'Content-Type' : 'application/json'
		};
			var Data ={
				'UserID': 18
			};

		const response = await fetch(APIURL,{
				method: 'POST',
				headers: headers,
				body: JSON.stringify(Data)
			});

	
		const json	= await response.json();
		state = json.Notifications;
	} 

	return (
		<View style={styles.view}>
			<Text>User ID: { userID }</Text>
			<Text>Events | Notifications</Text>
			<FlatList 
				data={state}
				keyExtractor={(x, i) => i}
				renderItem={({item}) => 
					<Text> {item.name} | {item.topic} </Text>}
			/>
		</View>
		);		
	

}

