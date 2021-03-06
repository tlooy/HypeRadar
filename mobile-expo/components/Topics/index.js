// https://www.youtube.com/watch?v=IuYo009yc8w


import React, { Component, useEffect } from 'react';
import { View, Text, FlatList, Linking, TouchableOpacity} from 'react-native';
import styles from './style';

export default class Topics extends Component {

	constructor(props) {
		super(props);
	}
	
	userID = "";
	state = { data : [] };
/* TODO: got to get this to work.  Currently returns an 'Unexpected token' error on second '('.

	useEffect(() => {
		this.fetchTopics();		
	}, []);
*/
	componentDidMount() {
		this.fetchTopics();	
	}

	fetchTopics = async() => {
	this.userID = this.props.navigation.getParam('UserID');
		var APIURL = global.environment + "fetchTopics.php";

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
		this.setState({data: json.Topics});
	} 

	render() {
		return (
			<View style={styles.view}>
			<Text>UserID: { this.userID }{"\n"}</Text>
			<Text>Events   |   Topics</Text>
			<Text>----------------------------</Text>

				<FlatList 
					data={this.state.data}
					keyExtractor={(x, i) => i}
					renderItem={({item}) => ( 
						<TouchableOpacity onPress={() => Linking.openURL(item.url)}>
							<Text style={{color: 'blue'}}>
								{item.name} | {item.topic}
								{"\n"}
							</Text>
						</TouchableOpacity>
					)}
				/>
			</View>
		);			
	}
}

