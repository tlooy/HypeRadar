// https://www.youtube.com/watch?v=IuYo009yc8w


import React, { Component } from 'react';
import { View, Text, FlatList} from 'react-native';
import styles from './style';

export default class Notifications extends Component {
	state = {
//			data : [{name: 'bob'}, {name:'tom'}, {name:'suz'}, {name:'chels'}, {name:'andrew'}]
			data : []
	};
/* TODO: got to get this to work.  Currently returns an 'Unexpected token' error.
	useEffect(() => {
		this.fetchNotifications();		
	});
*/

	componentDidMount() {
		this.fetchNotifications();	
	}

	fetchNotifications = async() => {
		const response = await fetch("http://10.0.0.40/HypeRadar/mobile-expo/backend/fetchNotifications.php");
		const json	= await response.json();
		this.setState({data: json.Notifications});
	} 

	render() {
		return (
			<View style={styles.view}>
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

